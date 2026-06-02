<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'company_name'        => $this->company_name,
            'trade_name'          => $this->trade_name,
            'supplier_code'       => $this->supplier_code,
            'tax_number'          => $this->tax_number,
            'registration_number' => $this->registration_number,
            'contact_person'      => $this->contact_person,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'mobile'              => $this->mobile,
            'fax'                 => $this->fax,
            'website'             => $this->website,
            'address_line1'       => $this->address_line1,
            'address_line2'       => $this->address_line2,
            'city'                => $this->city,
            'state'               => $this->state,
            'postal_code'         => $this->postal_code,
            'country'             => $this->country,
            'payment_terms'       => $this->payment_terms,
            'currency'            => $this->currency,
            'credit_limit'        => $this->credit_limit,
            'payment_due_days'    => $this->payment_due_days,
            'bank_name'           => $this->bank_name,
            'bank_account_name'   => $this->bank_account_name,
            'bank_account_number' => $this->bank_account_number,
            'bank_sort_code'      => $this->bank_sort_code,
            'bank_swift_code'     => $this->bank_swift_code,
            'notes'               => $this->notes,
            'metadata'            => $this->metadata,
            'is_active'           => $this->is_active,
            'is_preferred'        => $this->is_preferred,
            'rating'              => $this->rating,
            'created_at'          => $this->created_at?->toISOString(),
            'updated_at'          => $this->updated_at?->toISOString(),
            'deleted_at'          => $this->deleted_at?->toISOString(),
        ];
    }
}
