<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Str;

class BrandService
{
    /**
     * Get paginated brands with filters.
     */

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Brand::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['is_featured'] ?? null, fn($q, $featured) => $q->where('is_featured', $featured))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('sort_order')->paginate($perPage);
    }

    /**
     * Create a new brand.
     */

    public function create(array $data): Brand
    {
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $count = 1;
        while (Brand::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        return Brand::create($data);
    }

    /**
     * Update an existing brand.
     */
    public function update(Brand $brand, array $data): Brand
    {
        if (empty($data['slug']) && isset($data['name']) && $data['name'] !== $brand->name) {
            $data['slug'] = Str::slug($data['name']);
            // Ensure uniqueness
            $originalSlug = $data['slug'];
            $count = 1;
            while (Brand::where('slug', $data['slug'])->where('id', '!=', $brand->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        $brand->update($data);
        return $brand->fresh();
    }

    /**
     * Soft delete a brand.
     */
    public function delete(Brand $brand): bool
    {
        // Check if any products are associated
        if ($brand->products()->exists()) {
            throw new \Exception('Cannot delete brand with associated products.');
        }
        return $brand->delete();
    }

    /**
     * Restore a soft-deleted brand.
     */
    public function restore(int $id): bool
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        return $brand->restore();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Brand $brand): bool
    {
        $brand->is_active = !$brand->is_active;
        return $brand->save();
    }
}
