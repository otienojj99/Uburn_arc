<?php

namespace App\Services;

use App\Models\Discount;

class DiscountService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Discount::query()
            ->with('freeProduct')
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['applies_to'] ?? null, fn($q, $applies) => $q->where('applies_to', $applies))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Discount
    {
        return Discount::create($data);
    }

    public function update(Discount $discount, array $data): Discount
    {
        $discount->update($data);
        return $discount->fresh();
    }

    public function delete(Discount $discount): bool
    {
        // Optional: check if discount is used in orders before deleting
        return $discount->delete();
    }

    public function restore(int $id): bool
    {
        $discount = Discount::onlyTrashed()->findOrFail($id);
        return $discount->restore();
    }

    public function toggleActive(Discount $discount): bool
    {
        $discount->is_active = !$discount->is_active;
        return $discount->save();
    }
}
