<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    /**
     * Get paginated suppliers with filters.
     */

    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Supplier::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('supplier_code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%");
            })
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['is_preferred'] ?? null, fn($q, $pref) => $q->where('is_preferred', $pref))
            ->when($filters['country'] ?? null, fn($q, $country) => $q->where('country', $country))
            ->when($filters['rating'] ?? null, fn($q, $rating) => $q->where('rating', '>=', $rating))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('company_name')->paginate($perPage);
    }

    /**
     * Create a new supplier.
     */

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Update an existing supplier.
     */

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier->fresh();
    }

    /**
     * Soft delete a supplier.
     */

    public function delete(Supplier $supplier): bool
    {
        // Check if any purchases are associated
        if ($supplier->purchases()->exists()) {
            throw new \Exception('Cannot delete supplier with associated purchases.');
        }
        return $supplier->delete();
    }

    /**
     * Restore a soft-deleted supplier.
     */
    public function restore(int $id): bool
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        return $supplier->restore();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Supplier $supplier): bool
    {
        $supplier->is_active = !$supplier->is_active;
        return $supplier->save();
    }

    /**
     * Toggle preferred status.
     */
    public function togglePreferred(Supplier $supplier): bool
    {
        $supplier->is_preferred = !$supplier->is_preferred;
        return $supplier->save();
    }
}
