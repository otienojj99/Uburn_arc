<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'customer_code'      => $this->customer_code,
            'customer_type'      => $this->customer_type,
            'business_name'      => $this->business_name,
            'first_name'         => $this->first_name,
            'last_name'          => $this->last_name,
            'full_name'          => $this->full_name,
            'display_name'       => $this->display_name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'alternative_phone'  => $this->alternative_phone,
            'address_line1'      => $this->address_line1,
            'address_line2'      => $this->address_line2,
            'city'               => $this->city,
            'state'              => $this->state,
            'postal_code'        => $this->postal_code,
            'country'            => $this->country,
            'full_address'       => $this->full_address,
            'tax_number'         => $this->tax_number,
            'registration_number' => $this->registration_number,
            'website'            => $this->website,
            'currency'           => $this->currency,
            'language'           => $this->language,
            'credit_limit'       => $this->credit_limit,
            'payment_due_days'   => $this->payment_due_days,
            'account_balance'    => $this->account_balance,
            'loyalty_points'     => $this->loyalty_points,
            'loyalty_tier'       => $this->loyalty_tier,
            'marketing_emails'   => $this->marketing_emails,
            'marketing_sms'      => $this->marketing_sms,
            'last_login_at'      => $this->last_login_at?->toISOString(),
            'last_login_ip'      => $this->last_login_ip,
            'status'             => $this->status,
            'blocked_reason'     => $this->blocked_reason,
            'notes'              => $this->notes,
            'metadata'           => $this->metadata,
            'preferences'        => $this->preferences,
            'created_at'         => $this->created_at?->toISOString(),
            'updated_at'         => $this->updated_at?->toISOString(),
        ];
    }
}
