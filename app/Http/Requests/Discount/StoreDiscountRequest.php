<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
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
        return [
            'name'                          => 'required|string|max:255',
            'code'                          => 'nullable|string|max:50|unique:discounts,code',
            'description'                   => 'nullable|string',
            'type'                          => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping,fixed_price',
            'value' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:type,percentage,fixed_amount,fixed_price',
            ],
            'maximum_discount_amount'        => 'nullable|numeric|min:0',
            'buy_get_type' => [
                'nullable',
                'in:same_product,different_product',
                'required_if:type,buy_x_get_y',
            ],
            'buy_quantity'                   => 'nullable|integer|min:1|required_if:type,buy_x_get_y',
            'get_quantity'                    => 'nullable|integer|min:1|required_if:type,buy_x_get_y',
            'free_product_id'                 => 'nullable|exists:product_items,id|required_if:type,buy_x_get_y',
            'applies_to'                      => 'required|in:all_products,specific_products,specific_categories,specific_customers,first_purchase,minimum_purchase',
            'minimum_order_amount'            => 'nullable|numeric|min:0',
            'minimum_quantity'                => 'nullable|integer|min:1',
            'maximum_quantity'                => 'nullable|integer|min:1',
            'customer_eligibility'            => 'required|in:all,registered_only,guest_only,specific_groups,new_customers_only',
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
            'stacking_type'                    => 'nullable|in:no_stacking,stack_sequential,best_only',
            'starts_at'                        => 'required|date',
            'ends_at'                          => 'nullable|date|after_or_equal:starts_at',
            'valid_days_of_week'                => 'nullable|array',
            'valid_days_of_week.*'              => 'integer|between:0,6',
            'valid_from_time'                   => 'nullable',
            'date_format:H:i',
            'valid_to_time'                     =>  [
                'nullable',
                'date_format:H:i',
                'after:valid_from_time',
            ],
            'is_active'                         => 'sometimes|boolean',
            'is_public'                         => 'sometimes|boolean',
            'status'                            => 'required|in:draft,active,expired,disabled',
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
