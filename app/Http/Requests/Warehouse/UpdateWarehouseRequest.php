<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
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
        $warehouseId = $this->route('warehouse')->id ?? $this->warehouse;
        return [

            'name'          => 'sometimes|string|max:255',
            'code'          => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('warehouses', 'code')->ignore($warehouseId),
            ],
            'description'   => 'nullable|string',
            'address_line1' => 'sometimes|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'sometimes|string|max:100',
            'state'         => 'nullable|string|max:100',
            'postal_code'   => 'sometimes|string|max:20',
            'country'       => 'sometimes|string|max:100',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'nullable|email|max:255',
            'manager_name'  => 'nullable|string|max:255',
            'is_active'     => 'sometimes|boolean',
            'is_primary'    => 'sometimes|boolean',
            'metadata'      => 'nullable|array',

        ];
    }
}
