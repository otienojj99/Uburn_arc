<?php

namespace App\Models;

use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Warehouse extends Model
{

    use SoftDeletes, HasUserAudit;
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */


    protected $fillable = [
        'name',
        'code',
        'description',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'manager_name',
        'is_active',
        'is_primary',
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
        'is_active'  => 'boolean',
        'is_primary' => 'boolean',
        'metadata'   => 'array',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active'  => true,
        'is_primary' => false,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get all stock transactions that occurred in this warehouse.
     */

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    /**
     * Get all inventory counts performed in this warehouse.
     */

    public function inventoryCounts()
    {
        return $this->hasMany(InventoryCount::class);
    }

    /**
     * Get all products stocked in this warehouse (many-to-many).
     * Requires a product_warehouse pivot table.
     */

    public function products()
    {
        return $this->belongsToMany(ProductItem::class, 'product_warehouse')
            ->withPivot('stock_quantity', 'minimum_stock', 'maximum_stock', 'bin_location')
            ->withTimestamps();
    }

    /**
     * Get all stock transfers where this warehouse is the source.
     */
    public function sourceStockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'source_warehouse_id');
    }

    /**
     * Get all stock transfers where this warehouse is the destination.
     */

    public function destinationStockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'destination_warehouse_id');
    }

    // ========== SCOPES ==========

    /**
     * Scope to include only active warehouses.
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to include only primary warehouses.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to filter by country.
     */
    public function scopeInCountry($query, $country)
    {
        return $query->where('country', $country);
    }


    /**
     * Scope to filter by city.
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }


    // ========== ACCESSORS ==========

    /**
     * Get the full address as a single string.
     */

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get the display name (name with code).
     */

    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }
}
