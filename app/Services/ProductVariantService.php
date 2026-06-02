<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductVariantService
{
    public function  getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = ProductVariant::with('productItem')
            ->when($filters['product_item_id'] ?? null, fn($q, $id) => $q->where('product_item_id', $id))
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getForProduct(int $productId)
    {
        return ProductVariant::where('product_item_id', $productId)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): ProductVariant
    {
        return ProductVariant::create($data);
    }

    public function update(ProductVariant $variant, array $data): ProductVariant
    {
        $variant->update($data);
        return $variant->fresh();
    }

    public function delete(ProductVariant $variant): bool
    {
        // Optionally check if variant is used in sales/orders
        return $variant->delete();
    }

    public function restore(int $id): bool
    {
        $variant = ProductVariant::onlyTrashed()->findOrFail($id);
        return $variant->restore();
    }

    public function toggleActive(ProductVariant $variant): bool
    {
        $variant->is_active = !$variant->is_active;
        return $variant->save();
    }
}
