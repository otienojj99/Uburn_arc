<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    //
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'transfer_number',
        'source_warehouse_id',
        'destination_warehouse_id',
        'status',
        'request_date',
        'transfer_date',
        'expected_arrival_date',
        'completed_date',
        'total_items_count',
        'total_quantity',
        'reason',
        'notes',
        'metadata',
        'requested_by',
        'approved_by',
        'completed_by',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_date'          => 'date',
        'transfer_date'         => 'date',
        'expected_arrival_date' => 'date',
        'completed_date'        => 'date',
        'total_items_count'     => 'integer',
        'total_quantity'        => 'integer',
        'metadata'              => 'array',
        'deleted_at'            => 'datetime',
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
     * Get the source warehouse.
     */
    public function sourceWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    /**
     * Get the destination warehouse.
     */
    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    /**
     * Get the user who requested the transfer.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved the transfer.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who completed the transfer.
     */
    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the user who created this record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this record.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the items in this transfer.
     */
    public function items()
    {
        return $this->hasMany(StockTransferItems::class);
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
     * Scope to include transfers from a specific source warehouse.
     */
    public function scopeFromSource($query, $warehouseId)
    {
        return $query->where('source_warehouse_id', $warehouseId);
    }

    /**
     * Scope to include transfers to a specific destination warehouse.
     */
    public function scopeToDestination($query, $warehouseId)
    {
        return $query->where('destination_warehouse_id', $warehouseId);
    }

    /**
     * Scope to include transfers requested between dates.
     */
    public function scopeRequestedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('request_date', [$startDate, $endDate]);
    }

    /**
     * Scope to include transfers expected to arrive between dates.
     */
    public function scopeExpectedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('expected_arrival_date', [$startDate, $endDate]);
    }

    /**
     * Scope to include only active transfers (not cancelled/completed).
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the transfer is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the transfer is in transit.
     */
    public function getIsInTransitAttribute(): bool
    {
        return $this->status === 'in_transit';
    }

    /**
     * Check if the transfer is cancelled.
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the transfer is pending (draft/pending/approved).
     */
    public function getIsPendingAttribute(): bool
    {
        return in_array($this->status, ['draft', 'pending', 'approved']);
    }
}
