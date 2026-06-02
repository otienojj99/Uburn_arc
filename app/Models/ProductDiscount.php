<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'product_item_id',
        'discount_id',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the product item associated with this pivot.
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    /**
     * Get the discount associated with this pivot.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
