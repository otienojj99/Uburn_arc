<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $discountId = $this->route('discount')->id ?? $this->discount;
        return [
            'name'                          => 'sometimes|string|max:255',
            'code'                          => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('discounts', 'code')->ignore($discountId),
            ],
            'description'                   => 'nullable|string',
            'type'                          => 'sometimes|in:percentage,fixed_amount,buy_x_get_y,free_shipping,fixed_price',
            'value'                         => 'sometimes|numeric|min:0',
            'maximum_discount_amount'        => 'nullable|numeric|min:0',
            'buy_quantity'                   => 'nullable|integer|min:1|required_if:type,buy_x_get_y',
            'get_quantity'                    => 'nullable|integer|min:1|required_if:type,buy_x_get_y',
            'free_product_id'                 => 'nullable|exists:product_items,id|required_if:type,buy_x_get_y',
            'applies_to'                      => 'sometimes|in:all_products,specific_products,specific_categories,specific_customers,first_purchase,minimum_purchase',
            'minimum_order_amount'            => 'nullable|numeric|min:0',
            'minimum_quantity'                => 'nullable|integer|min:1',
            'maximum_quantity'                => 'nullable|integer|min:1',
            'customer_eligibility'            => 'sometimes|in:all,registered_only,guest_only,specific_groups,new_customers_only',
            'eligible_customer_groups'        => 'nullable|array',
            'eligible_customer_groups.*'      => 'string',
            'eligible_customer_ids'           => 'nullable|array',
            'eligible_customer_ids.*'         => 'integer|exists:customers,id',
            'eligible_product_ids'             => 'nullable|array',
            'eligible_product_ids.*'           => 'integer|exists:product_items,id',
            'eligible_category_ids'            => 'nullable|array',
            'eligible_category_ids.*'          => 'integer|exists:categories,id',
            'excluded_product_ids'             => 'nullable|array',
            'excluded_product_ids.*'           => 'integer|exists:product_items,id',
            'excluded_category_ids'            => 'nullable|array',
            'excluded_category_ids.*'          => 'integer|exists:categories,id',
            'usage_limit_per_coupon'           => 'nullable|integer|min:1',
            'usage_limit_per_customer'         => 'nullable|integer|min:1',
            'can_combine_with_other_discounts' => 'sometimes|boolean',
            'priority'                         => 'sometimes|integer',
            'stacking_type'                    => 'sometimes|in:no_stacking,stack_sequential,best_only',
            'starts_at'                        => 'sometimes|date',
            'ends_at'                          => 'nullable|date|after_or_equal:starts_at',
            'valid_days_of_week'                => 'nullable|array',
            'valid_days_of_week.*'              => 'integer|between:0,6',
            'valid_from_time'                   => 'nullable|date_format:H:i',
            'valid_to_time'                     => 'nullable|date_format:H:i|after:valid_from_time',
            'is_active'                         => 'sometimes|boolean',
            'is_public'                         => 'sometimes|boolean',
            'status'                            => 'sometimes|in:draft,active,expired,disabled',
            'display_text'                      => 'nullable|string|max:255',
            'banner_image'                      => 'nullable|string|max:255',
            'show_on_product_page'               => 'sometimes|boolean',
            'show_on_cart_page'                  => 'sometimes|boolean',
            'show_on_checkout'                   => 'sometimes|boolean',
            'first_purchase_only'                => 'sometimes|boolean',
            'new_registration_only'              => 'sometimes|boolean',
            'metadata'                           => 'nullable|array',
            'notes'                              => 'nullable|string',
        ];
    }
}
