<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                            => $this->id,
            'name'                          => $this->name,
            'code'                          => $this->code,
            'description'                   => $this->description,
            'type'                          => $this->type,
            'value'                         => $this->value,
            'maximum_discount_amount'        => $this->maximum_discount_amount,
            'buy_quantity'                   => $this->buy_quantity,
            'get_quantity'                    => $this->get_quantity,
            'free_product'                   => new ProductResource($this->whenLoaded('freeProduct')),
            'applies_to'                      => $this->applies_to,
            'minimum_order_amount'            => $this->minimum_order_amount,
            'minimum_quantity'                => $this->minimum_quantity,
            'maximum_quantity'                => $this->maximum_quantity,
            'customer_eligibility'            => $this->customer_eligibility,
            'eligible_customer_groups'        => $this->eligible_customer_groups,
            'eligible_customer_ids'           => $this->eligible_customer_ids,
            'eligible_product_ids'            => $this->eligible_product_ids,
            'eligible_category_ids'           => $this->eligible_category_ids,
            'excluded_product_ids'            => $this->excluded_product_ids,
            'excluded_category_ids'           => $this->excluded_category_ids,
            'usage_limit_per_coupon'          => $this->usage_limit_per_coupon,
            'usage_limit_per_customer'        => $this->usage_limit_per_customer,
            'total_used'                       => $this->total_used,
            'can_combine_with_other_discounts' => $this->can_combine_with_other_discounts,
            'priority'                         => $this->priority,
            'stacking_type'                    => $this->stacking_type,
            'starts_at'                        => $this->starts_at?->toISOString(),
            'ends_at'                          => $this->ends_at?->toISOString(),
            'last_used_at'                     => $this->last_used_at?->toISOString(),
            'valid_days_of_week'                => $this->valid_days_of_week,
            'valid_from_time'                   => $this->valid_from_time?->format('H:i'),
            'valid_to_time'                     => $this->valid_to_time?->format('H:i'),
            'is_active'                         => $this->is_active,
            'is_public'                         => $this->is_public,
            'status'                            => $this->status,
            'display_text'                      => $this->display_text,
            'banner_image'                      => $this->banner_image,
            'show_on_product_page'               => $this->show_on_product_page,
            'show_on_cart_page'                  => $this->show_on_cart_page,
            'show_on_checkout'                   => $this->show_on_checkout,
            'first_purchase_only'                => $this->first_purchase_only,
            'new_registration_only'              => $this->new_registration_only,
            'metadata'                           => $this->metadata,
            'notes'                              => $this->notes,
            'created_at'                         => $this->created_at?->toISOString(),
            'updated_at'                         => $this->updated_at?->toISOString(),
        ];
    }
}
