<?php
// app/Services/ProductLaunchService.php

namespace App\Services;

use App\Models\ProductLaunch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductLaunchService
{
    // ──────────────────────────────────────────────────────────
    // QUERIES
    // ──────────────────────────────────────────────────────────

    /**
     * Get the currently active launch for the homepage hero.
     */

    function getCurrent(): ?ProductLaunch{
        return ProductLaunch::current()
            ->with([
                'product.featuredImage',
                'product.images',
                'product.brand',
            ])
            ->first();
    }

     /**
     * Get upcoming (not yet live) launches.
     */
    public function getUpcoming(int $limit = 5): Collection
    {
        return ProductLaunch::upcoming()
            ->with(['product.featuredImage'])
            ->limit($limit)
            ->get();
    }

    /**
     * Admin: paginated list with optional filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        return ProductLaunch::query()
            ->with(['product.featuredImage'])
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            })
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when(isset($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed())
            ->orderByDesc('priority')
            ->orderByDesc('launches_at')
            ->paginate($perPage);
    }

    // ──────────────────────────────────────────────────────────
    // CRUD
    // ──────────────────────────────────────────────────────────

    public function create(array $data): ProductLaunch
    {
        return DB::transaction(function () use ($data) {
            $data = $this->handleHeroImage($data);

            // Fall back to sensible defaults
            $data['status']    = $data['status']    ?? 'scheduled';
            $data['is_active'] = $data['is_active'] ?? true;
            $data['priority']  = $data['priority']  ?? 0;

            return ProductLaunch::create($data);
        });
    }

    public function update(ProductLaunch $launch, array $data): ProductLaunch
    {
        return DB::transaction(function () use ($launch, $data) {
            // Handle explicit image removal
            if (!empty($data['remove_hero_image']) && empty($data['hero_image'])) {
                $this->deleteHeroImage($launch->hero_image_path);
                $data['hero_image_path'] = null;
            }
            unset($data['remove_hero_image']);

            // Handle new upload / existing path
            $data = $this->handleHeroImage($data, $launch);

            $launch->update($data);
            return $launch->fresh();
        });
    }

     public function delete(ProductLaunch $launch): bool
    {
        return $launch->delete();
    }

    public function restore(int $id): ProductLaunch
    {
        $launch = ProductLaunch::onlyTrashed()->findOrFail($id);
        $launch->restore();
        return $launch->fresh();
    }


    public function toggleActive(ProductLaunch $launch): ProductLaunch
    {
        $launch->update(['is_active' => !$launch->is_active]);
        return $launch->fresh();
    }

    // ──────────────────────────────────────────────────────────
    // SCHEDULER
    // ──────────────────────────────────────────────────────────

    /**
     * Flip scheduled launches to live when their time arrives.
     * Called by the scheduled command.
     */

    public function publishDue(): int
    {
        $count = 0;
        ProductLaunch:where('status', 'scheduled')
            ->where('launches_at', '<=', now())
            ->where(function ($q) {
            $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })

            ->each(function (ProductLaunch $launch) use (&$count) {
                $launch->update(['status' => 'live']);
                $launch->product?->update(['is_active' => true]);
                $count++;
            });


            return $count;
    }

    // ──────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Move an uploaded hero image into $data['hero_image_path'].
     * Deletes the previous one if replacing.
     */

    private function handleHeroImage(array $data, ?ProductLaunch $existing = null): array
    {
        if (isset($data['hero_image']) && $data['hero_image'] instanceof UploadedFile) {
            // Delete old image
            if ($existing && $existing->hero_image_path) {
                $this->deleteHeroImage($existing->hero_image_path);
            }

            $data['hero_image_path'] = $this->uploadHeroImage($data['hero_image']);
        }

        // Don't pass the raw file into the model
        unset($data['hero_image']);

        return $data;
    }

    private function uploadHeroImage(UploadedFile $file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'launches/' . $filename;

        Storage::disk('s3')->put($path, file_get_contents($file), 'public');

        return $path;
    }

    private function deleteHeroImage(?string $path): void
    {
        if ($path) {
            delete_storage_file($path, 's3'); // your helper from earlier
        }
    }
}