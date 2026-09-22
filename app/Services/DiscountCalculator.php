<?php

namespace App\Services;

use App\Models\ProductItem;
use App\Models\Discount;

class DiscountCalculator
{
    public function calculate(ProductItem $product, int $quantity, Discount $discount): float
    {
        $price = $product->selling_price;
        switch ($discount->type) {
            case 'percentage':
                return $price * ($discount->value / 100) * $quantity;
            case 'fixed_amount':
                return $discount->value * $quantity;
            case 'fixed_price':
                return $price > $discount->value ? ($price - $discount->value) * $quantity : 0;
            default:
                return 0;
        }
    }
}
