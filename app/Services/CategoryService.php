<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService
{
    // ─────────────────────────────────────────────────────────
    // READ
    // ─────────────────────────────────────────────────────────

    /**
     * Get paginated categories, optionally with trashed.
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Category::query()
            ->with(['parent', 'children'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->when(isset($filters['parent_id']), fn($q) => $q->where('parent_id', $filters['parent_id']))
            ->when($filters['roots_only'] ?? false, fn($q) => $q->whereNull('parent_id'))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('sort_order')->paginate($perPage);
    }

    /**
     * Get full category tree (nested).
     */
    public function getTree(bool $activeOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $query = Category::query()
            ->whereNull('parent_id')
            ->with(['descendants'])
            ->orderBy('sort_order');

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    /**
     * Get all descendants of a category (recursive).
     */
    public function getDescendants(Category $category): \Illuminate\Database\Eloquent\Collection
    {
        return $category->descendants()->get();
    }

    // ─────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────

    public function create(array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $this->validateParentId($data['parent_id'] ?? null);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']); // returns path
        }

        return Category::create($data);
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────

    // public function update(Category $category, array $data): Category
    // {
    //     // 1. Regenerate slug if name changed and no slug supplied
    //     if (empty($data['slug']) && isset($data['name']) && $data['name'] !== $category->name) {
    //         $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
    //     }

    //     // 2. Validate parent (prevents cycles + self-parenting)
    //     if (array_key_exists('parent_id', $data)) {
    //         $this->validateParentId($data['parent_id'], $category->id);
    //         $this->preventCycle($category, $data['parent_id']);
    //     }

    //     // 3. Handle image upload
    //     if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
    //         $this->deleteImage($category->image);
    //         $data['image'] = $this->uploadImage($data['image']);
    //     } else {
    //         unset($data['image']);
    //     }

    //     $category->update($data);
    //     return $category->fresh();
    // }

    public function update(Category $category, array $data): Category
    {
        // slug logic...

        if (array_key_exists('parent_id', $data)) {
            $this->validateParentId($data['parent_id']);
            $this->preventCycle($category, $data['parent_id']);
        }

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteImage($category->image);
            $data['image'] = $this->uploadImage($data['image']);
        } else {
            unset($data['image']);
        }

        $category->update($data);
        return $category->fresh();
    }

    // ─────────────────────────────────────────────────────────
    // DELETE / RESTORE
    // ─────────────────────────────────────────────────────────

    public function delete(Category $category): bool
    {
        // Check direct children (including trashed)
        if ($category->children()->withTrashed()->exists()) {
            throw new \Exception('Cannot delete category with subcategories.');
        }

        // Check products
        if ($category->products()->exists()) {
            throw new \Exception('Cannot delete category with products.');
        }

        return $category->delete();
    }

    public function restore(int $id): bool
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        // Restore parent if it's trashed
        if ($category->parent_id) {
            $parent = Category::withTrashed()->find($category->parent_id);
            if ($parent && $parent->trashed()) {
                $parent->restore();
            }
        }

        return $category->restore();
    }

    public function toggleActive(Category $category): bool
    {
        $category->is_active = !$category->is_active;
        return $category->save();
    }

    // ─────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────

    /**
     * Generate a unique slug.
     */
    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (
            Category::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Validate that a parent_id exists and is a root (not a child itself).
     * This keeps the tree to 2 levels max.
     * If you want unlimited depth, remove the "is root" check.
     */
    private function validateParentId(?int $parentId): void
    {
        if (!$parentId) return;

        $parent = Category::find($parentId);
        if (!$parent) {
            throw new \Exception('Parent category does not exist.');
        }

        // OPTIONAL: enforce max 2 levels
        if ($parent->parent_id !== null) {
            throw new \Exception('Parent category must be a root category (no nested subcategories).');
        }
    }

    /**
     * Prevent cycles: a category cannot be its own ancestor.
     */
    private function preventCycle(Category $category, ?int $newParentId): void
    {
        if (!$newParentId) return;

        if ($newParentId === $category->id) {
            throw new \Exception('A category cannot be its own parent.');
        }

        // Walk up the chain from the new parent, and make sure we don't hit $category
        $current = Category::find($newParentId);
        $depth = 0;
        $maxDepth = 10; // safety

        while ($current && $depth < $maxDepth) {
            if ($current->id === $category->id) {
                throw new \Exception('This would create a circular category hierarchy.');
            }
            $current = $current->parent;
            $depth++;
        }
    }

    /**
     * Upload category image to S3.
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
    // ✅ After
    private function deleteImage(?string $path): void
    {
        delete_storage_file($path, 's3');
    }
}
