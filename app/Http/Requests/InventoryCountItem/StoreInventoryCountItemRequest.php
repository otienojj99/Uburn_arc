<?php

namespace App\Http\Requests\InventoryCountItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryCountItemRequest extends FormRequest
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
            'inventory_count_id' => 'required|exists:inventory_counts,id',
            'product_item_id'    => 'required|exists:product_items,id',
            'barcode_id'         => 'nullable|exists:barcodes,id',
            'system_quantity'    => 'required|integer|min:0',
            'counted_quantity'   => 'required|integer|min:0',
            'status'             => 'sometimes|in:pending,counted,verified,adjusted',
            'counted_by'         => 'nullable|exists:users,id',
            'verified_by'        => 'nullable|exists:users,id',
            'notes'              => 'nullable|string',
            'metadata'           => 'nullable|array',
        ];
    }
}
