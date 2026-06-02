<?php

namespace App\Services;

use App\Models\InventoryCount;
use Illuminate\Support\Facades\Auth;

class InventoryCountService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = InventoryCount::with(['warehouse', 'creator', 'approver'])
            ->when($filters['warehouse_id'] ?? null, fn($q, $id) => $q->where('warehouse_id', $id))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['from_date'] ?? null, fn($q, $date) => $q->whereDate('count_date', '>=', $date))
            ->when($filters['to_date'] ?? null, fn($q, $date) => $q->whereDate('count_date', '<=', $date))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): InventoryCount
    {
        return InventoryCount::create($data);
    }

    public function update(InventoryCount $count, array $data): InventoryCount
    {
        $count->update($data);
        return $count->fresh();
    }

    public function delete(InventoryCount $count): bool
    {
        // Optionally check if count has items
        if ($count->items()->exists()) {
            throw new \Exception('Cannot delete an inventory count that already has items.');
        }
        return $count->delete();
    }

    public function restore(int $id): bool
    {
        $count = InventoryCount::onlyTrashed()->findOrFail($id);
        return $count->restore();
    }

    public function changeStatus(InventoryCount $count, string $status): InventoryCount
    {
        if (!in_array($status, ['draft', 'in_progress', 'completed', 'approved', 'cancelled'])) {
            throw new \InvalidArgumentException('Invalid status.');
        }
        $count->status = $status;
        if ($status === 'completed' && !$count->completed_at) {
            $count->completed_at = now();
        }
        if ($status === 'approved') {
            $count->approved_by = Auth::id();
        }
        $count->save();
        return $count->fresh();
    }
}
