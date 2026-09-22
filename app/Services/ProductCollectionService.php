<?php


namespace App\Services;

use App\Models\ProductItem;
use Illuminate\Support\Collection;

class ProductCollectionService
{

    /**
     * Get new arrivals (is_new = true or created within last X days).
     */
    public function getNewArrivals(int $limit = 10): Collection
    {
        return ProductItem::newArrivals()
            ->with(['category', 'brand', 'featuredImage'])
            ->active()
            ->limit($limit)
            ->get();
    }

    /**
     * Get best sellers (ordered by sales_count DESC).
     */

    public function getBestSellers(int $limit = 10): Collection
    {
        return ProductItem::bestSellers()
            ->with(['category', 'brand', 'featuredImage'])
            ->active()
            ->limit($limit)
            ->get();
    }

    public function getTrending(int $limit = 10): Collection
    {
        return ProductItem::trending()
            ->with(['category', 'brand', 'featuredImage'])
            ->active()
            ->limit($limit)
            ->get();
    }

    /**
     * Get featured products.
     */
    public function getFeatured(int $limit = 10): Collection
    {
        return ProductItem::featured()
            ->with(['category', 'brand', 'featuredImage'])
            ->limit($limit)
            ->get();
    }
}
