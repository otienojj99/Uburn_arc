<?php

namespace App\Services;

use App\Models\ProductItem;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Get paginated products with filters.
     */

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = ProductItem::with([
            'category',
            'brand',
            'primarySupplier',
            'primaryWarehouse',
            'images',
            'variants',
            'audiences',
        ])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->when($filters['category_id'] ?? null, fn($q, $catId) => $q->where('category_id', $catId))
            ->when($filters['brand_id'] ?? null, fn($q, $brandId) => $q->where('brand_id', $brandId))
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('selling_price', '>=', $price))
            ->when($filters['max_price'] ?? null, fn($q, $price) => $q->where('selling_price', '<=', $price))
            ->when($filters['low_stock'] ?? false, fn($q) => $q->lowStock())
            ->when($filters['out_of_stock'] ?? false, fn($q) => $q->outOfStock())
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed())
            ->when(
                $filters['audience_id'] ?? null,
                function ($q, $audienceId) {
                    $q->whereHas('audiences', function ($query) use ($audienceId) {
                        $query->where('audiences.id', $audienceId);
                    });
                }
            );

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new product.
     */

    public function create(array $data): ProductItem
    {
        $audienceIds = $data['audience_ids'] ?? [];

        unset($data['audience_ids']);
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure unique slug by appending random suffix if needed

        $originalSlug = $data['slug'];
        $count = 1;

        while (ProductItem::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        $product = ProductItem::create($data);

        if (!empty($audienceIds)) {
            $product->audiences()->sync($audienceIds);
        }

        return $product->load('audiences');
    }

    /**
     * Update an existing product.
     */

    public function update(ProductItem $product, array $data): ProductItem
    {


        $audienceIds = $data['audience_ids'] ?? null;

        unset($data['audience_ids']);
        // Handle slug regeneration only if name changed and slug not provided

        if (empty($data['slug']) && isset($data['name']) && $data['name'] != $product->name) {
            $data['slug'] = Str::slug($data['name']);
            // Check uniqueness and adjust
            $originalSlug = $data['slug'];
            $count = 1;
            while (ProductItem::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }
        $product->update($data);

        if ($audienceIds !== null) {
            $product->audiences()->sync($audienceIds);
        }

        return $product->load('audiences');
    }

    /**
     * Soft delete a product.
     */

    public function delete(ProductItem $product): bool
    {
        // Optionally check if product has sales/purchase references
        if ($product->saleItems()->exists() || $product->purchaseItems()->exists()) {
            throw new \Exception('Cannot delete product with associated sales or purchases.');
        }
        return $product->delete();
    }

    /**
     * Restore a soft-deleted product.
     */

    public function restore(int $id): bool
    {
        $product = ProductItem::onlyTrashed()->findOrFail($id);
        return $product->restore();
    }

    /**
     * Toggle active status.
     */

    public function toggleActive(ProductItem $product): bool
    {
        $product->is_active = !$product->is_active;
        return $product->save();
    }
}
