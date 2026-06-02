<?php

namespace App\Http\Requests\InventoryCountItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryCountItemRequest extends FormRequest
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
            'barcode_id'       => 'nullable|exists:barcodes,id',
            'counted_quantity' => 'sometimes|integer|min:0',
            'status'           => 'sometimes|in:pending,counted,verified,adjusted',
            'counted_by'       => 'nullable|exists:users,id',
            'verified_by'      => 'nullable|exists:users,id',
            'notes'            => 'nullable|string',
            'metadata'         => 'nullable|array'
        ];
    }
}
