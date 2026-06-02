<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'product_item_id',
        'sku',
        'name',
        'attributes',
        'price_adjustment',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attributes'       => 'array',
        'price_adjustment' => 'decimal:2',
        'price'            => 'decimal:2',
        'stock'            => 'integer',
        'is_active'        => 'boolean',
        'deleted_at'       => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the parent product item.
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function image()
    {
        return $this->belongsTo(ProductImage::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope to include only active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========== ACCESSORS ==========

    /**
     * Get the final price (base price + adjustment, or override price if set).
     * This assumes the ProductItem model has a 'selling_price' attribute.
     */
    public function getFinalPriceAttribute()
    {
        if ($this->price !== null) {
            return $this->price;
        }
        return ($this->productItem->selling_price ?? 0) + $this->price_adjustment;
    }
}
