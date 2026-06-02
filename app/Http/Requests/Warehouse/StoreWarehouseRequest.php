<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            //
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:50|unique:warehouses,code',
            'description'   => 'nullable|string',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'nullable|string|max:100',
            'postal_code'   => 'required|string|max:20',
            'country'       => 'required|string|max:100',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'manager_name'  => 'nullable|string|max:255',
            'is_active'     => 'sometimes|boolean',
            'is_primary'    => 'sometimes|boolean',
            'metadata'      => 'nullable|array',
        ];
    }
}
