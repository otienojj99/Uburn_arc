<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SaleItem extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */


    protected $fillable = [
        'sale_id',
        'product_item_id',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'total',
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
        'quantity'    => 'integer',
        'unit_price'  => 'decimal:2',
        'discount'    => 'decimal:2',
        'tax'         => 'decimal:2',
        'total'       => 'decimal:2',
        'metadata'    => 'array',
        'deleted_at'  => 'datetime',
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
     * Get the parent sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product item sold.
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
     * Scope to include items from a specific sale.
     */
    public function scopeForSale($query, $saleId)
    {
        return $query->where('sale_id', $saleId);
    }

    /**
     * Scope to include items that are pending (not shipped/delivered/cancelled/returned).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ========== ACCESSORS ==========

    /**
     * Get the line total (already stored, but can recalc if needed).
     */
    public function recalcTotal(): float
    {
        return ($this->unit_price * $this->quantity) - $this->discount + $this->tax;
    }

    /**
     * Check if the item is shipped.
     */
    public function getIsShippedAttribute(): bool
    {
        return $this->status === 'shipped';
    }

    /**
     * Check if the item is delivered.
     */

    public function getIsDeliveredAttribute(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the item is returned.
     */
    public function getIsReturnedAttribute(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Check if the item is cancelled.
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }
}
