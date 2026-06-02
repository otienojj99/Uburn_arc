<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService
{
    /**
     * Get paginated categories, optionally with trashed.
     */

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Category::query()
            ->with(['parent', 'children'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['parent_id'] ?? null, fn($q, $parentId) => $q->where('parent_id', $parentId))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('sort_order')->paginate($perPage);
    }

    /**
     * Upload category image to S3 and return the path.
     */
    private function uploadImage(UploadedFile $image): string
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = 'categories/' . $filename;
        Storage::disk('s3')->put($path, file_get_contents($image), 'public');
        return $path;
    }

    /**
     * Delete image from S3.
     */
    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }

    /**
     * Create a new category.
     */

    public function create(array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Category::create($data);
    }

    /**
     * Update an existing category.
     */



    public function update(Category $category, array $data): Category
    {
        // If slug is not provided and name changed, we might generate a new slug?
        // But let's keep it simple – only update what's given.
        if (empty($data['slug']) && isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
            // Ensure uniqueness
            $originalSlug = $data['slug'];
            $count = 1;
            while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle image upload – delete old if new image is uploaded
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
            $this->deleteImage($category->image);
            // Upload new
            $data['image'] = $this->uploadImage($data['image']);
        } else {
            // If no new image, remove the key to avoid overwriting with null
            unset($data['image']);
        }

        $category->update($data);
        return $category->fresh();
    }

    /**
     * Soft delete a category.
     */

    public function delete(Category $category): bool
    {
        // Optionally check if category has products or children before deleting.
        if ($category->products()->exists() || $category->children()->exists()) {
            throw new \Exception('Cannot delete category with products or subcategories.');
        }

        return $category->delete();
    }

    /**
     * Restore a soft-deleted category.
     */

    public function restore(int $id): bool
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        return $category->restore();
    }

    /**
     * Toggle active status.
     */

    public function toggleActive(Category $category): bool
    {
        $category->is_active = !$category->is_active;
        return $category->save();
    }
}
