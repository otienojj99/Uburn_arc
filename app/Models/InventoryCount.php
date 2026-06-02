<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryCount extends Model
{
    use SoftDeletes, HasUserAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'count_reference',
        'warehouse_id',
        'created_by',
        'approved_by',
        'status',
        'count_date',
        'completed_at',
        'notes',
        'metadata',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'count_date'   => 'date',
        'completed_at' => 'datetime',
        'metadata'     => 'array',
        'deleted_at'   => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'draft',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the warehouse being counted.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the user who created this inventory count.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this inventory count.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the items (count lines) for this inventory count.
     */
    public function items()
    {
        return $this->hasMany(InventoryCountItem::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope to filter by status.
     */
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to include counts for a specific warehouse.
     */
    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope to include counts scheduled on or after a date.
     */
    public function scopeScheduledFrom($query, $date)
    {
        return $query->where('count_date', '>=', $date);
    }

    /**
     * Scope to include counts completed between dates.
     */
    public function scopeCompletedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('completed_at', [$startDate, $endDate]);
    }

    /**
     * Scope to include only completed counts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to include only counts that are in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the count is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the count is approved.
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the count is in progress.
     */
    public function getIsInProgressAttribute(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Get the total number of items counted.
     * (Requires loading items or summing a column; can be used after loading.)
     */
    public function getTotalCountedItemsAttribute(): int
    {
        if (!$this->relationLoaded('items')) {
            return 0;
        }
        return $this->items->count();
    }

    /**
     * Get the count of discrepancies (items where counted ≠ system).
     */
    public function getDiscrepancyCountAttribute(): int
    {
        if (!$this->relationLoaded('items')) {
            return 0;
        }
        return $this->items->filter(function ($item) {
            return $item->difference != 0;
        })->count();
    }
}
