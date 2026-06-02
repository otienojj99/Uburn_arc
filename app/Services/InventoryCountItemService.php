<?php

namespace App\Services;

use App\Models\InventoryCountItem;
use Illuminate\Support\Facades\DB;

class InventoryCountItemService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = InventoryCountItem::with(['inventoryCount', 'productItem', 'barcode', 'counter', 'verifier'])
            ->when($filters['inventory_count_id'] ?? null, fn($q, $id) => $q->where('inventory_count_id', $id))
            ->when($filters['product_item_id'] ?? null, fn($q, $id) => $q->where('product_item_id', $id))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['discrepancy'] ?? null, function ($q, $disc) {
                if ($disc === 'yes') {
                    $q->where('difference', '!=', 0);
                } elseif ($disc === 'no') {
                    $q->where('difference', 0);
                }
            })
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): InventoryCountItem
    {
        // Calculate difference
        $data['difference'] = $data['counted_quantity'] - $data['system_quantity'];
        return InventoryCountItem::create($data);
    }

    public function update(InventoryCountItem $item, array $data): InventoryCountItem
    {
        if (isset($data['counted_quantity'])) {
            // Recalculate difference
            $data['difference'] = $data['counted_quantity'] - $item->system_quantity;
        }
        $item->update($data);
        return $item->fresh();
    }

    public function delete(InventoryCountItem $item): bool
    {
        return $item->delete();
    }

    public function restore(int $id): bool
    {
        $item = InventoryCountItem::onlyTrashed()->findOrFail($id);
        return $item->restore();
    }

    public function markAsCounted(InventoryCountItem $item, int $countedBy, int $countedQuantity): InventoryCountItem
    {
        $item->counted_quantity = $countedQuantity;
        $item->difference = $countedQuantity - $item->system_quantity;
        $item->status = 'counted';
        $item->counted_by = $countedBy;
        $item->save();
        return $item->fresh();
    }

    public function verify(InventoryCountItem $item, int $verifiedBy): InventoryCountItem
    {
        $item->status = 'verified';
        $item->verified_by = $verifiedBy;
        $item->save();
        return $item->fresh();
    }

    public function adjust(InventoryCountItem $item): InventoryCountItem
    {
        // This would create a stock transaction (handled elsewhere)
        $item->status = 'adjusted';
        $item->save();
        return $item->fresh();
    }
}
