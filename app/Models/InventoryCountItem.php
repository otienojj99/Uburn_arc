<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCountItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'inventory_count_id',
        'product_item_id',
        'barcode_id',
        'system_quantity',
        'counted_quantity',
        'difference',
        'status',
        'counted_by',
        'verified_by',
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
        'system_quantity'  => 'integer',
        'counted_quantity' => 'integer',
        'difference'       => 'integer',
        'metadata'         => 'array',
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
     * Get the parent inventory count.
     */
    public function inventoryCount()
    {
        return $this->belongsTo(InventoryCount::class);
    }

    /**
     * Get the product item being counted.
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    /**
     * Get the barcode used for counting (if any).
     */
    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }

    /**
     * Get the user who counted this item.
     */
    public function counter()
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    /**
     * Get the user who verified this item.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
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
     * Scope to include items that have discrepancies (difference != 0).
     */
    public function scopeWithDiscrepancy($query)
    {
        return $query->where('difference', '!=', 0);
    }

    /**
     * Scope to include items without discrepancies.
     */
    public function scopeWithoutDiscrepancy($query)
    {
        return $query->where('difference', 0);
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the item has been counted.
     */
    public function getIsCountedAttribute(): bool
    {
        return $this->status === 'counted' || $this->status === 'verified' || $this->status === 'adjusted';
    }

    /**
     * Check if the item is verified.
     */
    public function getIsVerifiedAttribute(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Check if the item has been adjusted.
     */
    public function getIsAdjustedAttribute(): bool
    {
        return $this->status === 'adjusted';
    }

    /**
     * Get the discrepancy type (overage, shortage, or exact).
     */
    public function getDiscrepancyTypeAttribute(): string
    {
        if ($this->difference > 0) {
            return 'overage';
        } elseif ($this->difference < 0) {
            return 'shortage';
        }
        return 'exact';
    }
}
