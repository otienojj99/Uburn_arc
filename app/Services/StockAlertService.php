<?php

namespace App\Services;

use App\Models\StockAlert;

class StockAlertService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = StockAlert::with(['productItem', 'warehouse', 'resolver'])
            ->when($filters['product_item_id'] ?? null, fn($q, $id) => $q->where('product_item_id', $id))
            ->when($filters['warehouse_id'] ?? null, fn($q, $id) => $q->where('warehouse_id', $id))
            ->when($filters['alert_type'] ?? null, fn($q, $type) => $q->where('alert_type', $type))
            ->when($filters['severity'] ?? null, fn($q, $sev) => $q->where('severity', $sev))
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['is_resolved'] ?? null, fn($q, $resolved) => $q->where('is_resolved', $resolved))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): StockAlert
    {
        return StockAlert::create($data);
    }

    public function update(StockAlert $alert, array $data): StockAlert
    {
        $alert->update($data);
        return $alert->fresh();
    }

    public function delete(StockAlert $alert): bool
    {
        return $alert->delete();
    }

    public function restore(int $id): bool
    {
        $alert = StockAlert::onlyTrashed()->findOrFail($id);
        return $alert->restore();
    }

    public function resolve(StockAlert $alert, array $data): StockAlert
    {
        $data['is_resolved'] = true;
        $data['resolved_at'] = now();
        $alert->update($data);
        return $alert->fresh();
    }
}
