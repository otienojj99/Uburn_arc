<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $barcode
 * @property int $product_item_id
 * @property string $type
 * @property string|null $format
 * @property bool $is_primary
 * @property bool $is_active
 * @property string|null $notes
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read string $formatted_barcode
 * @property-read bool $is2_d
 * @property-read bool $is_numeric
 * @property-read \App\Models\ProductItem $productItem
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode ofType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode primary()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode withBarcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Barcode withoutTrashed()
 */
	class Barcode extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $cover_image
 * @property string|null $website
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_active
 * @property bool $is_featured
 * @property int $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $metadata
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereWebsite($value)
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $parent_id
 * @property bool $is_active
 * @property int $sort_order
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read mixed $full_path
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductItem> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $customer_code
 * @property string $customer_type
 * @property string|null $business_name
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $alternative_phone
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $email_verified_at
 * @property string|null $verification_token
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property string|null $tax_number
 * @property string|null $registration_number
 * @property string|null $website
 * @property string $currency
 * @property string $language
 * @property numeric|null $credit_limit
 * @property int $payment_due_days
 * @property numeric $account_balance
 * @property int $loyalty_points
 * @property string $loyalty_tier
 * @property bool $marketing_emails
 * @property bool $marketing_sms
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string $status
 * @property string|null $blocked_reason
 * @property string|null $notes
 * @property string|null $metadata
 * @property string|null $preferences
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAccountBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAlternativePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBlockedReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLoyaltyPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLoyaltyTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMarketingEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMarketingSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePaymentDueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereWebsite($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string|null $description
 * @property string $type
 * @property numeric $value
 * @property numeric|null $maximum_discount_amount
 * @property int|null $buy_quantity
 * @property int|null $get_quantity
 * @property int|null $free_product_id
 * @property string $applies_to
 * @property numeric|null $minimum_order_amount
 * @property int|null $minimum_quantity
 * @property int|null $maximum_quantity
 * @property string $customer_eligibility
 * @property string|null $eligible_customer_groups
 * @property string|null $eligible_customer_ids
 * @property string|null $eligible_product_ids
 * @property string|null $eligible_category_ids
 * @property string|null $excluded_product_ids
 * @property string|null $excluded_category_ids
 * @property int|null $usage_limit_per_coupon
 * @property int|null $usage_limit_per_customer
 * @property int $total_used
 * @property bool $can_combine_with_other_discounts
 * @property int $priority
 * @property string $stacking_type
 * @property string $starts_at
 * @property string|null $ends_at
 * @property string|null $last_used_at
 * @property string|null $valid_days_of_week
 * @property string|null $valid_from_time
 * @property string|null $valid_to_time
 * @property bool $is_active
 * @property bool $is_public
 * @property string $status
 * @property string|null $display_text
 * @property string|null $banner_image
 * @property bool $show_on_product_page
 * @property bool $show_on_cart_page
 * @property bool $show_on_checkout
 * @property bool $first_purchase_only
 * @property bool $new_registration_only
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $metadata
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereAppliesTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereBannerImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereBuyQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCanCombineWithOtherDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereCustomerEligibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereDisplayText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEligibleCategoryIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEligibleCustomerGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEligibleCustomerIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEligibleProductIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereExcludedCategoryIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereExcludedProductIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereFirstPurchaseOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereFreeProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereGetQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereMaximumDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereMaximumQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereMinimumOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereMinimumQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereNewRegistrationOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereShowOnCartPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereShowOnCheckout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereShowOnProductPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereStackingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereTotalUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUsageLimitPerCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereUsageLimitPerCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereValidDaysOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereValidFromTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereValidToTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discount whereValue($value)
 */
	class Discount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $count_reference
 * @property int $warehouse_id
 * @property int|null $created_by
 * @property int|null $approved_by
 * @property string $status
 * @property string $count_date
 * @property string|null $completed_at
 * @property string|null $notes
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereCountDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereCountReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCount whereWarehouseId($value)
 */
	class InventoryCount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $inventory_count_id
 * @property int $product_item_id
 * @property int|null $barcode_id
 * @property int $system_quantity
 * @property int $counted_quantity
 * @property int $difference
 * @property string $status
 * @property int|null $counted_by
 * @property int|null $verified_by
 * @property string|null $notes
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereBarcodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereCountedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereCountedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereDifference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereInventoryCountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereSystemQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCountItem whereVerifiedBy($value)
 */
	class InventoryCountItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_item_id
 * @property int $discount_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductDiscount whereUpdatedAt($value)
 */
	class ProductDiscount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_item_id
 * @property string $image_path
 * @property string|null $file_name
 * @property string|null $mime_type
 * @property int|null $file_size
 * @property int $sort_order
 * @property string|null $thumbnail_path
 * @property string|null $medium_path
 * @property string|null $large_path
 * @property string|null $alt_text
 * @property string|null $title
 * @property string|null $caption
 * @property bool $is_primary
 * @property bool $is_active
 * @property int|null $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $formatted_file_size
 * @property-read string $large_url
 * @property-read string $medium_url
 * @property-read string $thumbnail_url
 * @property-read string $url
 * @property-read \App\Models\ProductItem $productItem
 * @property-read \App\Models\User|null $uploader
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage order()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage primary()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereLargePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereMediumPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUploadedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage withoutTrashed()
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property numeric|null $cost_price
 * @property numeric $selling_price
 * @property numeric|null $compare_price
 * @property numeric|null $wholesale_price
 * @property int $current_stock
 * @property int $minimum_stock
 * @property int|null $maximum_stock
 * @property int|null $reorder_point
 * @property int $safety_stock
 * @property int|null $category_id
 * @property int|null $brand_id
 * @property int|null $primary_supplier_id
 * @property int|null $primary_warehouse_id
 * @property string $unit
 * @property numeric|null $weight
 * @property numeric|null $length
 * @property numeric|null $width
 * @property numeric|null $height
 * @property bool $is_active
 * @property bool $is_tracked
 * @property bool $allow_backorder
 * @property bool $is_featured
 * @property bool $is_new
 * @property bool $is_on_sale
 * @property string|null $tax_class
 * @property string|null $hs_code
 * @property string|null $country_of_origin
 * @property string|null $barcode
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $featured_image
 * @property array<array-key, mixed>|null $gallery_images
 * @property int $views_count
 * @property int $sales_count
 * @property array<array-key, mixed>|null $attributes
 * @property array<array-key, mixed>|null $variations
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Barcode> $barcodes
 * @property-read int|null $barcodes_count
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\ProductImage|null $featuredImage
 * @property-read string $display_name
 * @property-read mixed $final_price
 * @property-read bool $is_low_stock
 * @property-read bool $is_out_of_stock
 * @property-read bool $is_overstocked
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Barcode|null $primaryBarcode
 * @property-read \App\Models\Supplier|null $primarySupplier
 * @property-read \App\Models\Warehouse|null $primaryWarehouse
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $purchaseItems
 * @property-read int|null $purchase_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SaleItem> $saleItems
 * @property-read int|null $sale_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockAlert> $stockAlerts
 * @property-read int|null $stock_alerts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockTransaction> $stockTransactions
 * @property-read int|null $stock_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Supplier> $suppliers
 * @property-read int|null $suppliers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warehouse> $warehouses
 * @property-read int|null $warehouses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem byBrand($brandId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem inCategory($categoryId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem lowStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem outOfStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem overStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem tracked()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereAllowBackorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereComparePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCountryOfOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereGalleryImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereHsCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereIsOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereIsTracked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereMaximumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem wherePrimarySupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem wherePrimaryWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereReorderPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereSafetyStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereSalesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereTaxClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereVariations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereWholesalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductItem withoutTrashed()
 */
	class ProductItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariant query()
 */
	class ProductVariant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $supplier_id
 * @property string $purchase_number
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $expected_delivery_date
 * @property \Illuminate\Support\Carbon|null $delivery_date
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $shipping_cost
 * @property numeric $other_cost
 * @property numeric $total
 * @property string $currency
 * @property string|null $payment_terms
 * @property string $payment_status
 * @property string|null $shipping_method
 * @property string|null $tracking_number
 * @property string|null $shipping_address
 * @property string|null $notes
 * @property string|null $internal_notes
 * @property int|null $requested_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\User|null $creator
 * @property-read bool $is_fully_received
 * @property-read bool $is_overdue
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $requester
 * @property-read \App\Models\Supplier $supplier
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase expectedBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase fromSupplier($supplierId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereExpectedDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereOtherCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePurchaseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase withoutTrashed()
 */
	class Purchase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $purchase_id
 * @property int $product_item_id
 * @property int $quantity_ordered
 * @property int $quantity_received
 * @property numeric $unit_cost
 * @property numeric $discount
 * @property numeric $tax
 * @property numeric $total
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $expected_delivery_date
 * @property array<array-key, mixed>|null $metadata
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read bool $is_fully_received
 * @property-read int $remaining_quantity
 * @property-read \App\Models\ProductItem $product
 * @property-read \App\Models\Purchase $purchase
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem forPurchase($purchaseId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereExpectedDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereQuantityOrdered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereQuantityReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseItem withoutTrashed()
 */
	class PurchaseItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_number
 * @property int $customer_id
 * @property string $order_date
 * @property string|null $required_date
 * @property string|null $shipped_date
 * @property string|null $delivered_date
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $shipping_cost
 * @property numeric $discount
 * @property numeric $total
 * @property string $currency
 * @property string $payment_status
 * @property string|null $payment_method
 * @property string|null $transaction_id
 * @property string|null $shipping_method
 * @property string|null $tracking_number
 * @property string|null $shipping_address
 * @property string|null $billing_address
 * @property string|null $customer_notes
 * @property string|null $staff_notes
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereCustomerNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDeliveredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereRequiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStaffNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sale whereUpdatedBy($value)
 */
	class Sale extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sale_id
 * @property int $product_item_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $discount
 * @property numeric $tax
 * @property numeric $total
 * @property string $status
 * @property string|null $metadata
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SaleItem whereUpdatedBy($value)
 */
	class SaleItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_item_id
 * @property int|null $warehouse_id
 * @property string $alert_type
 * @property int $threshold_value
 * @property int $current_value
 * @property string $severity
 * @property bool $is_active
 * @property bool $is_resolved
 * @property string|null $resolved_at
 * @property int|null $resolved_by
 * @property string|null $resolution_notes
 * @property string|null $metadata
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereAlertType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereCurrentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereIsResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereResolvedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereThresholdValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAlert whereWarehouseId($value)
 */
	class StockAlert extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_item_id
 * @property int|null $warehouse_id
 * @property int|null $user_id
 * @property int $quantity_change
 * @property int $stock_before
 * @property int $stock_after
 * @property string $type
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property string|null $batch_number
 * @property string|null $expiry_date
 * @property string|null $reason
 * @property string|null $notes
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereQuantityChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereStockAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereStockBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransaction whereWarehouseId($value)
 */
	class StockTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $transfer_number
 * @property int $source_warehouse_id
 * @property int $destination_warehouse_id
 * @property string $status
 * @property string $request_date
 * @property string|null $transfer_date
 * @property string|null $expected_arrival_date
 * @property string|null $completed_date
 * @property int $total_items_count
 * @property int $total_quantity
 * @property string|null $reason
 * @property string|null $notes
 * @property string|null $metadata
 * @property int|null $requested_by
 * @property int|null $approved_by
 * @property int|null $completed_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereCompletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereDestinationWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereExpectedArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereSourceWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereTotalItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereTotalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereTransferDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereTransferNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransfer whereUpdatedBy($value)
 */
	class StockTransfer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $stock_transfer_id
 * @property int $product_item_id
 * @property int $quantity_requested
 * @property int $quantity_shipped
 * @property int $quantity_received
 * @property string $status
 * @property string|null $metadata
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereQuantityReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereQuantityRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereQuantityShipped($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereStockTransferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockTransferItems whereUpdatedBy($value)
 */
	class StockTransferItems extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $company_name
 * @property string|null $trade_name
 * @property string $supplier_code
 * @property string|null $tax_number
 * @property string|null $registration_number
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $fax
 * @property string|null $website
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string $country
 * @property string|null $payment_terms
 * @property string $currency
 * @property numeric|null $credit_limit
 * @property int $payment_due_days
 * @property string|null $bank_name
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property string|null $bank_sort_code
 * @property string|null $bank_swift_code
 * @property string|null $notes
 * @property array<array-key, mixed>|null $metadata
 * @property bool $is_active
 * @property bool $is_preferred
 * @property int|null $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $bank_details
 * @property-read string $full_address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Purchase> $purchases
 * @property-read int|null $purchases_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier inCountry(string $country)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier minRating(int $rating)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier preferred()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankSortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereBankSwiftCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsPreferred($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePaymentDueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePaymentTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereRegistrationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereSupplierCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereTradeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property string $address_line1
 * @property string|null $address_line2
 * @property string $city
 * @property string|null $state
 * @property string $postal_code
 * @property string $country
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $manager_name
 * @property bool $is_active
 * @property bool $is_primary
 * @property string|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Warehouse whereUpdatedAt($value)
 */
	class Warehouse extends \Eloquent {}
}

