<?php

namespace App\Services;

use App\Models\Warehouse;

class WarehouseService
{
    /**
     * Get paginated warehouses with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Warehouse::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            })
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['is_primary'] ?? null, fn($q, $primary) => $q->where('is_primary', $primary))
            ->when($filters['country'] ?? null, fn($q, $country) => $q->where('country', $country))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('name')->paginate($perPage);
    }

    /**
     * Create a new warehouse.
     */
    public function create(array $data): Warehouse
    {
        return Warehouse::create($data);
    }

    /**
     * Update an existing warehouse.
     */
    public function update(Warehouse $warehouse, array $data): Warehouse
    {
        $warehouse->update($data);
        return $warehouse->fresh();
    }

    /**
     * Soft delete a warehouse.
     */
    public function delete(Warehouse $warehouse): bool
    {
        // Check if warehouse has any stock or active transactions
        if ($warehouse->stockTransactions()->exists() || $warehouse->products()->exists()) {
            throw new \Exception('Cannot delete warehouse with associated stock or transactions.');
        }
        return $warehouse->delete();
    }

    /**
     * Restore a soft-deleted warehouse.
     */
    public function restore(int $id): bool
    {
        $warehouse = Warehouse::onlyTrashed()->findOrFail($id);
        return $warehouse->restore();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Warehouse $warehouse): bool
    {
        $warehouse->is_active = !$warehouse->is_active;
        return $warehouse->save();
    }

    /**
     * Toggle primary status.
     * Optionally, you may want to ensure only one primary warehouse exists.
     */
    public function togglePrimary(Warehouse $warehouse): bool
    {
        // If setting to primary, remove primary from others?
        if (!$warehouse->is_primary) {
            Warehouse::where('is_primary', true)->update(['is_primary' => false]);
        }
        $warehouse->is_primary = !$warehouse->is_primary;
        return $warehouse->save();
    }
}
