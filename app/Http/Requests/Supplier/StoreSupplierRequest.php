<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'company_name'        => 'required|string|max:255',
            'trade_name'          => 'nullable|string|max:255',
            'supplier_code'       => 'required|string|max:50|unique:suppliers,supplier_code',
            'tax_number'          => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'contact_person'      => 'nullable|string|max:255',
            'email'               => 'nullable|email|max:255',
            'phone'               => 'nullable|string|max:50',
            'mobile'              => 'nullable|string|max:50',
            'fax'                 => 'nullable|string|max:50',
            'website'             => 'nullable|url|max:255',
            'address_line1'       => 'nullable|string|max:255',
            'address_line2'       => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:100',
            'state'               => 'nullable|string|max:100',
            'postal_code'         => 'nullable|string|max:20',
            'country'             => 'nullable|string|max:100',
            'payment_terms'       => 'nullable|string|max:100',
            'currency'            => 'nullable|string|max:10',
            'credit_limit'        => 'nullable|numeric|min:0',
            'payment_due_days'    => 'nullable|integer|min:0',
            'bank_name'           => 'nullable|string|max:255',
            'bank_account_name'   => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_sort_code'      => 'nullable|string|max:50',
            'bank_swift_code'     => 'nullable|string|max:50',
            'notes'               => 'nullable|string',
            'metadata'            => 'nullable|array',
            'is_active'           => 'sometimes|boolean',
            'is_preferred'        => 'sometimes|boolean',
            'rating'              => 'nullable|integer|min:1|max:5',
        ];
    }
}
