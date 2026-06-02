<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'product_item_id',
        'warehouse_id',
        'alert_type',
        'threshold_value',
        'current_value',
        'severity',
        'is_active',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'metadata',
        'created_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'threshold_value' => 'integer',
        'current_value'   => 'integer',
        'is_active'       => 'boolean',
        'is_resolved'     => 'boolean',
        'resolved_at'     => 'datetime',
        'metadata'        => 'array',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */

    protected $attributes = [
        'severity'    => 'warning',
        'is_active'   => true,
        'is_resolved' => false,
    ];

     // ========== RELATIONSHIPS ==========

    /**
     * Get the product item that this alert belongs to.
     */

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }


    /**
     * Get the warehouse that this alert belongs to (if any).
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    /**
     * Get the user who resolved this alert.
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Get the user who created this alert (if system-generated, may be null).
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    // ========== SCOPES ==========

    /**
     * Scope to filter by alert type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('alert_type', $type);
    }


    /**
     * Scope to filter by severity.
     */
    public function scopeOfSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }


    /**
     * Scope to include only active (not resolved) alerts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
     * Scope to include only resolved alerts.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope to include alerts for a specific product.
     */

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_item_id', $productId);
    }


    /**
     * Scope to include alerts for a specific warehouse.
     */
    public function scopeInWareHouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // ========== ACCESSORS ==========

    /**
     * Check if the alert is critical.
     */

    public function  getIsCriticalAttribute(): bool
    {
        return $this->severity === 'critical';
    }

    /**
     * Check if the alert is warning.
     */
    public function getIsWarningAttribute(): bool
    {
        return $this->severity === 'warning';
    }


    /**
     * Get a human-readable description of the alert.
     */

    public function getDescriptionAttribute(): string
    {
        $productName = $this->productItem->name ?? 'Unknown product';
        $location = $this->warehouse ? " in {$this->warehouse->name}" : '';

        return match ($this->alert_type) {
            'low_stock' => "Low stock for {$productName}{$location} (current: {$this->current_value},
            threshold: {$this->threshold_value})",
            'out_of_stock' => "Out of stock for {$productName}{$location}",
            'overstock'    => "Overstock for {$productName}{$location} (current: {$this->current_value}, max: {$this->threshold_value})",
            'expiry_soon'  => "Product {$productName}{$location} expiring soon",
            'reorder_point' => "Reorder point reached for {$productName}{$location}",
            default        => "Stock alert for {$productName}{$location}",
        };
    }
}
