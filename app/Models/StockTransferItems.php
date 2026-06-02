<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'stock_transfer_id',
        'product_item_id',
        'quantity_requested',
        'quantity_shipped',
        'quantity_received',
        'status',
        'metadata',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity_requested' => 'integer',
        'quantity_shipped'   => 'integer',
        'quantity_received'  => 'integer',
        'metadata'           => 'array',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the parent stock transfer.
     */
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    /**
     * Get the product item being transferred.
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
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

    // ========== SCOPES ==========

    /**
     * Scope to filter by status.
     */
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to include items from a specific transfer.
     */
    public function scopeForTransfer($query, $transferId)
    {
        return $query->where('stock_transfer_id', $transferId);
    }

    /**
     * Scope to include items that are pending (not shipped/received/cancelled).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to include items that are partially shipped/received.
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    // ========== ACCESSORS ==========

    /**
     * Get the remaining quantity to be shipped.
     */
    public function getRemainingToShipAttribute(): int
    {
        return max(0, $this->quantity_requested - $this->quantity_shipped);
    }

    /**
     * Get the remaining quantity to be received (after shipped).
     */
    public function getRemainingToReceiveAttribute(): int
    {
        return max(0, $this->quantity_shipped - $this->quantity_received);
    }

    /**
     * Check if the item is fully shipped.
     */
    public function getIsFullyShippedAttribute(): bool
    {
        return $this->quantity_shipped >= $this->quantity_requested;
    }

    /**
     * Check if the item is fully received.
     */
    public function getIsFullyReceivedAttribute(): bool
    {
        return $this->quantity_received >= $this->quantity_shipped
            && $this->quantity_shipped > 0;
    }
}
