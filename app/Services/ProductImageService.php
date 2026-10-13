<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//use Intervention\Image\ImageManager; // optional for resizing

class ProductImageService
{

    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    /**
     * Upload an image and create a ProductImage record.
     */

    public function upload(UploadedFile $file, array $data): ProductImage
    {
        // Generate a unique filename
        $baseFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $productId = $data['product_item_id'];

        // defining paths
        $originalPath = "products/{$productId}/{$timestamp}_{$baseFilename}.{$extension}";
        $thumbnailPath = "products/{$productId}/{$timestamp}_{$baseFilename}_thumb.jpg";
        $mediumPath = "products/{$productId}/{$timestamp}_{$baseFilename}_medium.jpg";
        $largePath = "products/{$productId}/{$timestamp}_{$baseFilename}_large.jpg";

        // Store original in S3
        Storage::disk('s3')->put($originalPath, file_get_contents($file), 'public');

        // Optionally generate thumbnails using Intervention Image

        $image = $this->manager->read($file);
        // Thumbnail (150x150, crop to square)
        $thumb = clone $image;
        $thumb->cover(150, 150);
        Storage::disk('s3')->put($thumbnailPath, (string) $thumb->encode(new JpegEncoder(80)), 'public');

        // Medium (400x400, resize with aspect ratio)
        $medium = clone $image;
        $medium->resize(400, 400);
        Storage::disk('s3')->put($mediumPath, (string) $medium->encode(new JpegEncoder(85)), 'public');

        // Large (800x800, resize with aspect ratio)
        $large = clone $image;
        $large->resize(800, 800);
        Storage::disk('s3')->put($largePath, (string) $large->encode(new WebpEncoder(90)), 'public');

        // For now, we'll just store the original path

        $imageData = [
            'product_item_id' => $productId,
            'image_path'      => $originalPath,
            'thumbnail_path'  => $thumbnailPath,
            'medium_path'     => $mediumPath,
            'large_path'      => $largePath,
            'file_name'       => $file->getClientOriginalName(),
            'mime_type'       => $file->getMimeType(),
            'file_size'       => $file->getSize(),
            'sort_order'      => $data['sort_order'] ?? 0,
            'alt_text'        => $data['alt_text'] ?? null,
            'title'           => $data['title'] ?? null,
            'caption'         => $data['caption'] ?? null,
            'attributes'      => $data['attributes'] ?? null,
            'is_primary'      => $data['is_primary'] ?? false,
            'is_active'       => $data['is_active'] ?? true,
            // Allow caller to override (e.g., in tests or background jobs) or fall back to currently authenticated user
            'uploaded_by'     => $data['uploaded_by'] ?? Auth::id(),
        ];

        // If this image is set as primary, remove primary from others
        if ($imageData['is_primary']) {
            ProductImage::where('product_item_id', $data['product_item_id'])
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }
        \Log::info('Image data before create', $imageData);
        $image = ProductImage::create($imageData);
        \Log::info('Created image record', $image->toArray());
        return $image;
    }

    /**
     * Update image metadata (not the file itself).
     */

    public function update(ProductImage $image, array $data): ProductImage
    {
        // If setting as primary, remove primary from others
        if (isset($data['is_primary']) && $data['is_primary'] && !$image->is_primary) {
            ProductImage::where('product_item_id', $image->product_item_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $image->update($data);
        return $image->refresh();
    }

    /**
     * Delete an image (soft delete, but also remove from S3).
     */


    public function delete(ProductImage $image): bool
    {
        $paths = array_filter([
            $image->image_path,
            $image->thumbnail_path,
            $image->medium_path,
            $image->large_path,
        ]);

        if (!empty($paths)) {
            \Log::info('Deleting image paths', [
                'product_image_id' => $image->id,
                'paths' => $paths,
            ]);
            Storage::disk('s3')->delete($paths);
        } else {
            \Log::info('No image paths found to delete for ProductImage', [
                'product_image_id' => $image->id,
                'image_path' => $image->image_path,
                'thumbnail_path' => $image->thumbnail_path,
                'medium_path' => $image->medium_path,
                'large_path' => $image->large_path,
            ]);
        }

        $image->forceDelete(); // soft delete

        return true;
    }


    /**
     * Get images for a product.
     */

    public function getForProduct(int $productId, bool $activeOnly = true)
    {
        $query = ProductImage::where('product_item_id', $productId)
            ->orderBy('sort_order');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get();
    }
}
