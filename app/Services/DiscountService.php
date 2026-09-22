<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\ProductItem;
use Cache;
use Illuminate\Support\Collection;
use App\Services\DiscountCalculator;


class DiscountService
{

    protected BogoProcessor $bogoProcessor;
    protected DiscountCalculator $calculator;

    public function __construct(
        DiscountCalculator $calculator,
        BogoProcessor $bogoProcessor
    ) {
        $this->calculator = $calculator;
        $this->bogoProcessor = $bogoProcessor;
    }

    /**
     * Get paginated list of discounts for admin panel.
     */

    public function getPaginatedDiscounts(array $filters = [], int $perPage = 15)
    {
        $query = Discount::query()
            ->with('freeProduct')
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('name', 'like',  "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })

            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
            ->when($filters['applies_to'] ?? null, fn($q, $applies) => $q->where('applies_to', $applies))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    // public function getApplicableDiscountsForPaginatedProduct(array $filters = [], int $perPage = 15)
    // {
    //     $query = Discount::query()
    //         ->with('freeProduct')
    //         ->when($filters['search'] ?? null, function ($q, $search) {
    //             $q->where('name', 'like', "%{$search}%")
    //                 ->orWhere('code', 'like', "%{$search}%");
    //         })
    //         ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
    //         ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
    //         ->when($filters['is_active'] ?? null, fn($q, $active) => $q->where('is_active', $active))
    //         ->when($filters['applies_to'] ?? null, fn($q, $applies) => $q->where('applies_to', $applies))
    //         ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

    //     return $query->orderBy('created_at', 'desc')->paginate($perPage);
    // }

    /**
     * Get all active discounts that apply to a specific product.
     */

    public function getApplicableDiscountsForProduct(int $productId, int $quantity = 1): Collection
    {
        $cacheKey =  "discounts_product_{$productId}_{$quantity}";
        return Cache::remember($cacheKey, 3600, function () use ($productId, $quantity) {
            $product = ProductItem::find($productId);
            if (!$product) {
                return collect();
            }

            return Discount::active()
                ->where(function ($q) use ($product) {
                    // Apply eligibility rules
                    $q->where('applies_to', 'all_products')
                        ->orWhere(function ($sub) use ($product) {
                            $sub->where('applies_to', 'specific_products')
                                ->whereJsonContains('eligible_product_ids', $product->id);
                        })
                        ->orWhere(function ($sub) use ($product) {
                            $sub->where('applies_to', 'specific_categories')
                                ->whereJsonContains('eligible_category_ids', $product->category_id);
                        });
                })
                ->where(function ($q) use ($product) {
                    // Exclude if product is excluded
                    $q->whereNull('excluded_product_ids')
                        ->orWhereJsonDoesntContain('excluded_product_ids', $product->id);
                })
                ->where(function ($q) use ($quantity) {
                    // Minimum quantity requirement
                    if ($q->minimum_quantity) {
                        $q->where('minimum_quantity', '<=', $quantity);
                    }
                })
                ->orderBy('priority', 'desc')
                ->get();
        });
    }

    public function create(array $data): Discount
    {
        return Discount::create($data);
    }

    public function update(Discount $discount, array $data): Discount
    {
        $discount->update($data);
        return $discount->fresh();
    }

    public function delete(Discount $discount): bool
    {
        // Optional: check if discount is used in orders before deleting
        return $discount->delete();
    }

    public function restore(int $id): bool
    {
        $discount = Discount::onlyTrashed()->findOrFail($id);
        return $discount->restore();
    }

    public function toggleActive(Discount $discount): bool
    {
        $discount->is_active = !$discount->is_active;
        return $discount->save();
    }


    public function calculateCartDiscounts(array $cartItems): array
    {
        $activeBogos = Discount::active()
            ->where('type', 'buy_x_get_y')
            ->whereNotNull('free_product_id')
            ->get();

        $this->bogoProcessor->applyCrossProductBogo($cartItems, $activeBogos);
        // $productIds  = array_column($cartItems, 'product_id');
        // $allApplicableDiscounts = Discount::active()
        //     ->where(function ($q) use ($productIds) {
        //         // complicated: discount might apply to all, specific products, or categories.
        //         // We'll loop later per product.
        //     })->get();

        $totalDiscount = 0;
        $itemBreakdown = [];
        $appliedDiscounts = [];

        foreach ($cartItems as &$item) {
            $product = $item['product'] ?? ProductItem::find($item['product_id']);

            if (!$product) {
                continue;
            }
            $quantity = $item['quantity'];

            $discounts = $this->getApplicableDiscountsForProduct($product->id, $quantity);

            $bestDiscount = null;
            $bestAmount = 0;


            foreach ($discounts as $discount) {
                $calculated = $this->calculateProductDiscount($product, $quantity, $discount);
                if ($calculated['discount_amount']  > $bestAmount) {
                    $bestAmount = $calculated['discount_amount'];
                    $bestDiscount = $calculated;

                    if ($bestDiscount) {
                        $item['discount'] = $bestDiscount;
                        $item['final_price'] = $product->selling_price - ($bestDiscount['discount_amount'] / $quantity);

                        $totalDiscount += $bestDiscount['discount_amount'];
                        $appliedDiscounts[] = $bestDiscount['discount_id'];
                        $itemBreakdown[$product->id] = $bestDiscount;
                    }
                }
            }
        }

        return [
            'total_discount' => $totalDiscount,
            'item_breakdown' => $itemBreakdown,
            'applied_discounts' => $appliedDiscounts,
            'cart_items' => $cartItems, // updated with final prices
        ];
    }

    public function calculateProductDiscount(ProductItem $product, int $quantity, Discount $discount): array
    {
        $price = $product->selling_price;
        $result = [
            'discount_id' => $discount->id,
            'type' => $discount->type,
            'discount_amount' => 0,
            'final_price' => $price,
        ];

        if ($discount->type === 'buy_x_get_y') {
            $amount = $this->bogoProcessor->calculateSameProductBogo($product, $quantity, $discount);
        } else {
            $amount = $this->calculator->calculate($product, $quantity, $discount);
        }

        // Apply maximum discount cap if set
        if ($discount->maximum_discount_amount && $amount > $discount->maximum_discount_amount) {
            $amount = $discount->maximum_discount_amount;
        }

        $result['discount_amount'] = round($amount, 2);
        $result['final_price'] = round($price - ($amount / $quantity), 2);

        return $result;
    }



    public function getActiveBanners(int $limit = 5): Collection
    {
        $cacheKey = "discount_banners_{$limit}";
        return Cache::remember($cacheKey, 3600, function () use ($limit) {
            return Discount::active()
                ->hasBanner()
                ->orderBy('priority', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear all discount-related caches (call after create/update/delete).
     */
    public static function clearCaches(): void
    {
        // You can use a tag-based cache or just clear specific keys.
        // For simplicity, we flush the entire cache (or use a prefix pattern).
        Cache::flush(); // or use a more granular approach.
    }
}
