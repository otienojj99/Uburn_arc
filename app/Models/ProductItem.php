<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUserAudit;

class ProductItem extends Model
{
    use SoftDeletes, HasUserAudit;

    public const AUDIENCES = ['men', 'women', 'kids', 'unisex'];
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'short_description',
        'cost_price',
        'selling_price',
        'compare_price',
        'wholesale_price',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'reorder_point',
        'safety_stock',
        'category_id',
        'brand_id',
        'primary_supplier_id',
        'primary_warehouse_id',
        'unit',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
        'is_tracked',
        'allow_backorder',
        'is_featured',
        'is_new',
        'is_on_sale',
        'tax_class',
        'hs_code',
        'country_of_origin',
        'barcode',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'featured_image',
        // 'gallery_images',
        'views_count',
        'sales_count',
        'attributes',
        'variations',
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
        'cost_price'        => 'decimal:2',
        'selling_price'     => 'decimal:2',
        'compare_price'     => 'decimal:2',
        'wholesale_price'   => 'decimal:2',
        'current_stock'     => 'integer',
        'minimum_stock'     => 'integer',
        'maximum_stock'     => 'integer',
        'reorder_point'     => 'integer',
        'safety_stock'      => 'integer',
        'weight'            => 'decimal:2',
        'length'            => 'decimal:2',
        'width'             => 'decimal:2',
        'height'            => 'decimal:2',
        'is_active'         => 'boolean',
        'is_tracked'        => 'boolean',
        'allow_backorder'   => 'boolean',
        'is_featured'       => 'boolean',
        'is_new'            => 'boolean',
        'is_on_sale'        => 'boolean',
        'views_count'       => 'integer',
        'sales_count'       => 'integer',
        // 'gallery_images'    => 'array',
        'attributes'        => 'array',
        'variations'        => 'array',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'unit'          => 'piece',
        'current_stock' => 0,
        'minimum_stock' => 5,
        'safety_stock'  => 0,
        'is_active'     => true,
        'is_tracked'    => true,
        'views_count'   => 0,
        'sales_count'   => 0,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the category of this product.
     */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand of this product.
     */

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the primary supplier of this product.
     */

    public function primarySupplier()
    {
        return $this->belongsTo(Supplier::class, 'primary_supplier_id');
    }

    /**
     * Get the primary warehouse of this product.
     */

    public function primaryWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'primary_warehouse_id');
    }

    /**
     * Get all barcodes associated with this product.
     */

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }

    public function audiences()
    {
        return $this->belongsToMany(
            Audience::class,
            'audience_product_item'
        )->withTimestamps();
    }

    /**
     * Get the primary barcode (if the separate barcode field is used).
     * Note: The separate barcode field is stored directly on product_items.
     * This relationship is for the Barcode model (multiple barcodes).
     */

    public function primaryBarcode()
    {
        return $this->hasOne(Barcode::class)->where('is_primary', true);
    }

    /**
     * Get all stock transactions for this product.
     */

    public function stockTransactions()
    {
        return $this->hasMany((StockTransaction::class));
    }

    /**
     * Get all stock alerts for this product.
     */

    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    /**
     * Get all images for this product.
     */

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
    /**
     * Get all variants for this product.
     */

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the featured image (if any).
     */

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the purchase items (line items in purchase orders) for this product.
     */

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Get the sale items for this product.
     */

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Many-to-many relationship with warehouses (stock per warehouse).
     * Assuming you have a product_warehouse pivot table.
     */

    public function  warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
            ->withPivot('stock_quantity', 'minimum_stock', 'maximum_stock', 'bin_location')
            ->withTimestamps();;
    }

    /**
     * Many-to-many relationship with suppliers (if a product can be supplied by multiple suppliers).
     */

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products')
            ->withPivot('price', 'lead_time', 'is_preferred')
            ->withTimestamps();
    }

    // ========== MUTATORS ==========

    /**
     * 👇 2. ADD THE MUTATOR (place it after your relationships or scopes)
     * Ensure audience is always stored as an array.
     * Handles: null, single string, or array input.
     */

    

    


    // ========== SCOPES ==========

    /**
     * Scope to include only active products.
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to include products that are tracked for inventory.
     */

    public function scopeTracked($query)
    {
        return $query->where('is_tracked', true);
    }


    /**
     * Scope to include low stock products (current stock <= minimum stock).
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    /**
     * Scope to include out of stock products.
     */

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    /**
     * Scope to include overstock products (current stock >= maximum stock).
     */
    public function scopeOverStock($query)
    {
        $query->whereNotNull('maximum_stock')
            ->whereColumn('current_stock', '>=', 'maximum_stock');
    }

    /**
     * Scope to include products by category.
     */

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to include products by brand.
     */

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeForAudience($query, string $audience)
    {
        return $query->whereJsonContains('audience', $audience);
    }
    // ========== ACCESSORS ==========

    /**
     * Check if product is low stock.
     */

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * Check if product is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->current_stock <= 0;
    }

    /**
     * Check if product is overstocked.
     */
    public function getIsOverstockedAttribute(): bool
    {
        return $this->maximum_stock && $this->current_stock >= $this->maximum_stock;
    }

    /**
     * Get the final price (considering sales, wholesale, etc.).
     * For now, just return selling price; can be extended.
     */

    public function getFinalPriceAttribute()
    {
        // If on sale and compare price exists, maybe show compare price as original
        // For now, return selling price.
        return $this->selling_price;
    }

    /**
     * Get the product name with SKU.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->sku})";
    }

    public function scopeNewArrivals($query, int $days = 30)
    {
        return $query->where(function ($q) use ($days) {
            $q->where('is_new', true)
                ->orWhere('created_at', '>=', now()->subDays($days));
        })->active();
    }

    public function scopeBestSellers($query)
    {
        return $query->orderBy('sales_count', 'desc');
    }

    public function scopeTrending($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->active();
    }

    //     public function audiences()
    // {
    //     return $this->belongsToMany(
    //         Audience::class,
    //         'audience_product_item'
    //     )->withTimestamps();
    // }
}
