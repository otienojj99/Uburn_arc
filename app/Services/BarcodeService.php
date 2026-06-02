<?php

namespace App\Services;

use App\Models\Barcode;

class BarcodeService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Barcode::with('productItem')
            ->when($filters['product_item_id'] ?? null, fn($q, $id) => $q->where('product_item_id', $id))
            ->when($filters['barcode'] ?? null, fn($q, $barcode) => $q->where('barcode', 'like', "%{$barcode}%"))
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['is_primary'] ?? null, fn($q, $primary) => $q->where('is_primary', $primary))
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Barcode
    {
        // If setting as primary, unset existing primary for this product
        if ($data['is_primary'] ?? false) {
            Barcode::where('product_item_id', $data['product_item_id'])
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        return Barcode::create($data);
    }

    public function update(Barcode $barcode, array $data): Barcode
    {
        // If setting as primary and it's not already primary
        if (($data['is_primary'] ?? false) && !$barcode->is_primary) {
            Barcode::where('product_item_id', $barcode->product_item_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $barcode->update($data);
        return $barcode->fresh();
    }

    public function delete(Barcode $barcode): bool
    {
        return $barcode->delete();
    }

    public function restore(int $id): bool
    {
        $barcode = Barcode::onlyTrashed()->findOrFail($id);
        return $barcode->restore();
    }

    public function toggleActive(Barcode $barcode): bool
    {
        $barcode->is_active = !$barcode->is_active;
        return $barcode->save();
    }
}
