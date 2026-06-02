<?php

namespace App\Http\Requests\StockAlert;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockAlertRequest extends FormRequest
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
            'product_item_id'  => 'sometimes|exists:product_items,id',
            'warehouse_id'     => 'nullable|exists:warehouses,id',
            'alert_type'       => 'sometimes|in:low_stock,out_of_stock,overstock,expiry_soon,reorder_point',
            'threshold_value'  => 'sometimes|integer|min:0',
            'current_value'    => 'sometimes|integer|min:0',
            'severity'         => 'sometimes|in:info,warning,critical',
            'is_active'        => 'sometimes|boolean',
            'is_resolved'      => 'sometimes|boolean',
            'resolved_at'      => 'nullable|date',
            'resolved_by'      => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
            'metadata'         => 'nullable|array',
        ];
    }
}
