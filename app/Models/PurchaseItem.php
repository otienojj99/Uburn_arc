<?php

namespace App\Models;

use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'purchase_id',
        'product_item_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'discount',
        'tax',
        'total',
        'status',
        'expected_delivery_date',
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
        'quantity_ordered'        => 'integer',
        'quantity_received'       => 'integer',
        'unit_cost'               => 'decimal:2',
        'discount'                => 'decimal:2',
        'tax'                     => 'decimal:2',
        'total'                   => 'decimal:2',
        'expected_delivery_date'  => 'date',
        'metadata'                => 'array',
        'deleted_at'              => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */

    protected $attributes = [
        'status' => 'pending',
        'quantity_received' => 0,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the parent purchase.
     */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the product item being ordered.
     */

    public function product()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
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
     * Scope to include items from a specific purchase.
     */

    public function scopeForPurchase($query, $purchaseId)
    {
        return $query->where('purchase_id', $purchaseId);
    }

    /**
     * Scope to include items that are not fully received.
     */

    public function  scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'partial']);
    }

    // ========== ACCESSORS ==========

    /**
     * Get the remaining quantity to be received.
     */

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    /**
     * Check if the item is fully received.
     */

    public function getIsFullyReceivedAttribute(): bool
    {
        return $this->status === 'received';
    }

    /**
     * Get the total line value (already stored, but can recalc if needed).
     */

    public function recalcTotal(): float
    {
        return ($this->unit_cost * $this->quantity_ordered) - $this->discount + $this->tax;
    }
}
