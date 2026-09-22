<?php

namespace App\Models;

use App\Events\DiscountUpdated;
use App\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes, HasUserAudit;

    protected static function booted(): void
    {
        static::saved(function (Discount $discount): void {
            event(new DiscountUpdated($discount));
        });

        static::deleted(function (Discount $discount): void {
            event(new DiscountUpdated($discount));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'maximum_discount_amount',
        'buy_get_type',
        'buy_quantity',
        'get_quantity',
        'free_product_id',
        'applies_to',
        'minimum_order_amount',
        'minimum_quantity',
        'maximum_quantity',
        'customer_eligibility',
        'eligible_customer_groups',
        'eligible_customer_ids',
        'eligible_product_ids',
        'eligible_category_ids',
        'excluded_product_ids',
        'excluded_category_ids',
        'usage_limit_per_coupon',
        'usage_limit_per_customer',
        'total_used',
        'can_combine_with_other_discounts',
        'priority',
        'stacking_type',
        'starts_at',
        'ends_at',
        'last_used_at',
        'valid_days_of_week',
        'valid_from_time',
        'valid_to_time',
        'is_active',
        'is_public',
        'status',
        'display_text',
        'banner_image',
        'show_on_product_page',
        'show_on_cart_page',
        'show_on_checkout',
        'first_purchase_only',
        'new_registration_only',
        'created_by',
        'updated_by',
        'metadata',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value'                      => 'decimal:2',
        'maximum_discount_amount'    => 'decimal:2',
        'minimum_order_amount'       => 'decimal:2',
        'buy_quantity'               => 'integer',
        'get_quantity'               => 'integer',
        'minimum_quantity'           => 'integer',
        'maximum_quantity'           => 'integer',
        'usage_limit_per_coupon'     => 'integer',
        'usage_limit_per_customer'   => 'integer',
        'total_used'                 => 'integer',
        'priority'                   => 'integer',
        'is_active'                  => 'boolean',
        'is_public'                  => 'boolean',
        'can_combine_with_other_discounts' => 'boolean',
        'show_on_product_page'        => 'boolean',
        'show_on_cart_page'           => 'boolean',
        'show_on_checkout'            => 'boolean',
        'first_purchase_only'         => 'boolean',
        'new_registration_only'       => 'boolean',
        'starts_at'                   => 'datetime',
        'ends_at'                     => 'datetime',
        'last_used_at'                => 'datetime',
        'valid_from_time'             => 'datetime:H:i', // cast as time (Laravel uses datetime with format)
        'valid_to_time'               => 'datetime:H:i',
        'valid_days_of_week'          => 'array',
        'eligible_customer_groups'    => 'array',
        'eligible_customer_ids'       => 'array',
        'eligible_product_ids'        => 'array',
        'eligible_category_ids'       => 'array',
        'excluded_product_ids'        => 'array',
        'excluded_category_ids'       => 'array',
        'metadata'                    => 'array',
        'deleted_at'                  => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'total_used' => 0,
        'priority' => 0,
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Get the free product associated with BOGO discounts.
     */
    public function freeProduct()
    {
        return $this->belongsTo(ProductItem::class, 'free_product_id');
    }

    /**
     * Get the user who created this discount.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this discount.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Many-to-many relationships can be added if you use pivot tables
    // e.g., for eligible products, categories, customers.

    // ========== SCOPES ==========

    /**
     * Scope to include only active discounts (status = active and within date range).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->where('starts_at', '<=', now());
    }

    /**
     * Scope to filter by discount type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by promo code.
     */
    public function scopeWithCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Scope to include discounts that apply to a specific product.
     * (Checks applicable_to and eligible_product_ids.)
     */
    public function scopeApplicableToProduct($query, $productId)
    {
        return $query->where(function ($q) use ($productId) {
            $q->where('applies_to', 'all_products')
                ->orWhere(function ($sub) use ($productId) {
                    $sub->where('applies_to', 'specific_products')
                        ->whereJsonContains('eligible_product_ids', $productId);
                })
                ->orWhere(function ($sub) use ($productId) {
                    // for categories, you'd need to join or use a subquery
                    // simplified: we assume product's category_id is available
                    $sub->where('applies_to', 'specific_categories')
                        ->whereRaw("JSON_CONTAINS(eligible_category_ids, ?)", [json_encode($productId)]);
                    // Better: use a pivot or relationship
                });
        })->where(function ($q) use ($productId) {
            $q->whereNull('excluded_product_ids')
                ->orWhereJsonDoesntContain('excluded_product_ids', $productId);
        });
    }

    /**
     * Scope: discounts that have a banner image and should be shown publicly.
     */
    public function scopeHasBanner($query)
    {
        return $query->whereNotNull('banner_image')->where('is_public', true);
    }
    // ========== ACCESSORS ==========

    /**
     * Check if the discount is currently valid based on schedule.
     */
    public function getIsCurrentlyValidAttribute(): bool
    {
        $now = now();
        return $this->starts_at <= $now
            && ($this->ends_at === null || $this->ends_at >= $now)
            && $this->is_active
            && $this->status === 'active';
    }

    /**
     * Get the discount description with value formatted.
     */
    public function getFormattedValueAttribute(): string
    {
        return match ($this->type) {
            'percentage' => $this->value . '%',
            'fixed_amount' => number_format($this->value, 2) . ' ' . ($this->currency ?? 'NGN'),
            'buy_x_get_y' => "Buy {$this->buy_quantity} Get {$this->get_quantity} Free",
            'free_shipping' => 'Free Shipping',
            'fixed_price' => 'Fixed Price: ' . number_format($this->value, 2),
            default => (string) $this->value,
        };
    }

    public function getFreeProductAttribute()
    {
        return $this->free_product_id ? ProductItem::find($this->free_product_id) : null;
    }
}
