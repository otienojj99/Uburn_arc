<?php
// app/Services/DiscountCalculator.php

namespace App\Services;

use App\Models\Discount;
use App\Models\ProductItem;


class DiscountCalculator
{
    public function calculateProductDiscount(ProductItem $product, int $quantity, Discount $discount): array
    {
        $price = $product->selling_price;
        $result = [
            'discount_id' => $discount->id,
            'type' => $discount->type,
            'discount_amount' => 0,
            'final_price' => $price, // default
        ];

        switch ($discount->type) {
            case 'percentage':
                $discountAmount = $price * ($discount->value / 100) * $quantity;
                break;
            case 'fixed_amount':
                $discountAmount = $discount->value * $quantity;
                break;
            case 'fixed_price':
                if ($price > $discount->value) {
                    $discountAmount = ($price - $discount->value) * $quantity;
                } else {
                    $discountAmount = 0;
                }
                break;
            case 'buy_x_get_y':
                // For same-product BOGO
                $freeUnits = floor($quantity / $discount->buy_quantity) * $discount->get_quantity;
                $discountAmount = $price * $freeUnits;
                break;
            case 'free_shipping':
                // Not applicable per product, handled at cart level.
                $discountAmount = 0;
                break;
            default:
                $discountAmount = 0;
        }

        // Apply maximum discount cap if set
        if ($discount->maximum_discount_amount && $discountAmount > $discount->maximum_discount_amount) {
            $discountAmount = $discount->maximum_discount_amount;
        }

        $result['discount_amount'] = round($discountAmount, 2);
        $result['final_price'] = round($price - ($discountAmount / $quantity), 2);
        return $result;
    }
}
