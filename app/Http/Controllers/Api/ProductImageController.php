<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Http\Requests\ProductImage\UpdateProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use App\Traits\ApiResponseTrait;

class ProductImageController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductImageService $productImageService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(int $productId)
    {
        $images = $this->productImageService->getForProduct($productId);
        return $this->successResponse(
            ProductImageResource::collection($images)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request,  int $productId)
    {
        $data = $request->validated();
        $data['product_item_id'] = $productId;

        \Log::info($request->all());
        \Log::info($request->file('image'));
        $image = $this->productImageService->upload(
            $request->file('image'),
            // $request->validated()
            $data
        );



        return $this->successResponse(
            new ProductImageResource($image),
            'Image uploaded successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $product_image)
    {
        return $this->successResponse(new ProductImageResource($product_image));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductImageRequest $request, ProductImage $product_image)
    {
        //
        $image = $this->productImageService->update($product_image, $request->validated());

        return $this->successResponse(
            new ProductImageResource($image),
            'Image updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $productId, ProductImage $product_image)
    {
        if ($product_image->product_item_id !== $productId) {
            abort(404);
        }

        $this->productImageService->delete($product_image);
        return $this->successResponse(null, 'Image deleted successfully.');
    }


    /**
     * Set image as primary.
     */
    public function setPrimary(int $productId, ProductImage $product_image)
    {
        \Log::info('Setting image as primary', [
            'product_image_id' => $product_image->id,
            'product_item_id' => $product_image->product_item_id,
            'requested_product_id' => $productId,
        ]);
        if ($product_image->product_item_id !== $productId) {
            abort(404);
        }

        $this->productImageService->update($product_image, ['is_primary' => true]);

        \Log::info('Image set as primary', [
            'product_image_id' => $product_image->id,
            'product_item_id' => $product_image->product_item_id,
        ]);
        return $this->successResponse(
            new ProductImageResource($product_image),
            'Image set as primary.'
        );
    }
}
