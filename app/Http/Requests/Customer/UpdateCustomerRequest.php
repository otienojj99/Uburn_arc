<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')->id ?? $this->customer;

        return [
            'customer_code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('customers', 'customer_code')->ignore($customerId),
            ],
            'customer_type' => 'sometimes|in:individual,business,wholesale',
            'business_name' => 'nullable|string|max:255',
            'first_name'    => 'sometimes|string|max:255',
            'last_name'     => 'sometimes|string|max:255',
            'email'         => [
                'sometimes',
                'email',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'phone'             => 'nullable|string|max:50',
            'alternative_phone' => 'nullable|string|max:50',
            'password'          => 'nullable|confirmed|min:8',
            'address_line1'     => 'nullable|string|max:255',
            'address_line2'     => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'postal_code'       => 'nullable|string|max:20',
            'country'           => 'nullable|string|max:100',
            'tax_number'        => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'website'           => 'nullable|url|max:255',
            'currency'          => 'nullable|string|max:10',
            'language'          => 'nullable|string|max:10',
            'credit_limit'      => 'nullable|numeric|min:0',
            'payment_due_days'  => 'nullable|integer|min:0',
            'loyalty_points'    => 'nullable|integer|min:0',
            'loyalty_tier'      => 'nullable|in:bronze,silver,gold,platinum',
            'marketing_emails'  => 'sometimes|boolean',
            'marketing_sms'     => 'sometimes|boolean',
            'status'            => 'sometimes|in:active,inactive,blocked,pending',
            'blocked_reason'    => 'nullable|string|max:500',
            'notes'             => 'nullable|string',
            'metadata'          => 'nullable|array',
            'preferences'       => 'nullable|array',
        ];
    }
}
