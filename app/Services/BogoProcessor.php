<?php
// app/Services/BogoProcessor.php

namespace App\Services;

use App\Models\Discount;
use App\Models\ProductItem;
use Illuminate\Support\Collection;


class BogoProcessor
{
    /**
     * Calculate the discount for a BOGO discount on a single product line.
     * This covers "buy X, get Y free" of the SAME product.
     */

    public function calculateSameProductBogo(
        ProductItem $product,
        int $quantity,
        Discount $discount
    ): float {
        if ($discount->type !== 'buy_x_get_y') {
            return 0;
        }

        $buyQty = $discount->buy_quantity;
        $getQty = $discount->get_quantity;

        if ($buyQty <= 0 || $getQty <= 0) {
            return 0;
        }

        $groups = floor($quantity / $buyQty);
        $freeunits = $groups * $getQty;


        if ($discount->free_product_id & $discount->free_product_id != $product->id) {
            return 0;
        }

        return $freeunits * $product->selling_price;
    }

    public function applyCrossProductBogo(array &$cartItems, Collection $activeBogos): array
    {
        $additionalItems = [];

        foreach ($activeBogos as $bogo) {
            if ($bogo->type !== "buy_x_get_y" || !$bogo->free_product_id) {
                continue;
            }

            $eligibleItems = $this->findEligibleCartItems($cartItems, $bogo);
            if ($eligibleItems->isEmpty()) {
                continue;
            }

            // Calculate how many free units we owe

            $totalPaidQuantity = $eligibleItems->sum('quantity');
            $freeunits = floor($totalPaidQuantity / $bogo->buy_quantity) * $bogo->get_quantity;

            if ($freeunits <= 0) {
                continue;
            }

            // Get the free product model

            $freeProducts = ProductItem::find($bogo->free_product_id);
            if (!$freeProducts) {
                continue;
            }

            // Distribute free units: either add to existing cart item for free product,
            // or add a new "virtual" item.
            $this->addFreeProductToCart($cartItems, $freeProducts, $freeunits, $bogo);
        }

        return $additionalItems;
    }

    protected function findEligibleCartItems(array $cartItems, Discount $bogo): Collection
    {
        return collect($cartItems)->filter(function ($item) use ($bogo) {
            $product = $item['product'] ?? ProductItem::find($item['product_id']);
            if (!$product) {
                return false;
            }
            return $this->isProductEligibleForDiscount($product, $bogo);
        });
    }

    protected function isProductEligibleForDiscount(ProductItem $product, Discount $discount): bool
    {
        if ($discount->applies_to === 'all_products') {
            return true;
        }

        if ($discount->applies_to === 'specific_products') {
            $eligible = $discount->eligible_product_ids ?? [];

            if (!in_array($product->id, $eligible)) {
                return false;
            }
        }

        if ($discount->applies_to === 'specific_categories') {
            $catIds = $discount->eligible_category_ids ?? [];
            if (!$product->category_id || !in_array($product->category_id, $catIds)) {
                return false;
            }
        }

        $excludedProducts = $discount->excluded_product_ids ?? [];
        if (in_array($product->id, $excludedProducts)) {
            return false;
        }

        $excludedCats = $discount->excluded_category_ids ?? [];
        if ($product->category_id && in_array($product->category_id, $excludedCats)) {
            return false;
        }

        return true;
    }

    /**
     * Add free product to cart (either by increasing quantity of existing item,
     * or adding a new item with zero price).
     */
    protected function addFreeProductToCart(array $cartItems, ProductItem $freeProduct, int $freeunits, Discount $discount): void
    {
        $foundIndex = null;
        foreach ($cartItems as $index => &$item) {
            if ($item['product_id'] == $freeProduct->id) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex  !== null) {
            $cartItems[$foundIndex]["quantity"] += $freeunits;
            $cartItems[$foundIndex]['free_units'] = ($cartItems[$foundIndex]['free_units'] ?? 0) + $freeunits;
        } else {
            $cartItems[] = [
                'product_id' => $freeProduct->id,
                'product' => $freeProduct,
                'quantity' => $freeunits,
                'free_units' => $freeunits,
                'unit_price' => $freeProduct->selling_price,
                'final_price' => 0, // will be handled in discount calculation
                'discount_applied' => [
                    'discount_id' => $discount->id,
                    'type' => 'buy_x_get_y',
                    'free_units' => $freeunits,
                ],
            ];
        }
    }
}
