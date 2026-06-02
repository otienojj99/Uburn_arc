<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{

    use SoftDeletes, HasUserAudit;
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'product_item_id',
        'warehouse_id',
        'user_id',
        'quantity_change',
        'stock_before',
        'stock_after',
        'type',
        'reference_type',
        'reference_id',
        'batch_number',
        'expiry_date',
        'reason',
        'notes',
        'metadata',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'quantity_change' => 'integer',
        'stock_before'    => 'integer',
        'stock_after'     => 'integer',
        'expiry_date'     => 'date',
        'metadata'        => 'array',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

     // ========== RELATIONSHIPS ==========

    /**
     * Get the product item involved in this transaction.
     */

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    /**
     * Get the warehouse where this transaction occurred.
     */

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the user who performed this transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reference model (polymorphic).
     */

    public function refrence()
    {
        return $this->morphTo();
    }

    // ========== SCOPES ==========

    /**
     * Scope to filter by transaction type.
     */

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }


    /**
     * Scope to filter by product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_item_id', $productId);
    }

    /**
     * Scope to filter by warehouse.
     */

    public function scopeInWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope to filter by date range.
     */

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }


    /**
     * Scope to include only stock increases (positive quantity).
     */
    public function scopeStockIn($query)
    {
        return $query->where('quantity_change', '>', 0);
    }


    /**
     * Scope to include only stock decreases (negative quantity).
     */
    public function scopeStockOut($query)
    {
        return $query->where('quantity_change', '<', 0);
    }


    // ========== ACCESSORS ==========

    /**
     * Get the absolute quantity change (always positive).
     */

    public function  getAbsoluteQuantityAttribute(): int
    {
        return abs($this->quantity_change);
    }


    /**
     * Determine if this transaction is an increase.
     */
    public function getIsIncreaseAttribute(): bool
    {
        return $this->quantity_change > 0;
    }

    /**
     * Get a human-readable description of the transaction.
     */
    public function getDescriptionAttribute(): string
    {
        $direction = $this->quantity_change > 0 ? 'added to' : 'removed from';
        $product = $this->productItem->name ?? 'Unknown product';
        return abs($this->quantity_change) . " units {$direction} {$product}";
    }
}
