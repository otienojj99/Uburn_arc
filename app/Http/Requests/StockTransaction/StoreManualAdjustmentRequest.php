<?php

namespace App\Http\Requests\StockTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreManualAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_item_id' => 'required|exists:product_items,id',
            'warehouse_id'    => 'nullable|exists:warehouses,id',
            'quantity_change' => 'required|integer|not_in:0', // cannot be zero
            'reason'          => 'required|string|max:255',
            'notes'           => 'nullable|string',
            'batch_number'    => 'nullable|string|max:100',
            'expiry_date'     => 'nullable|date',
            'metadata'        => 'nullable|array',
        ];
    }
}
