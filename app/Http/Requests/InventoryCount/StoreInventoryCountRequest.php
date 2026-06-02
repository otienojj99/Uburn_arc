<?php

namespace App\Http\Requests\InventoryCount;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryCountRequest extends FormRequest
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
            'count_reference' => 'nullable|string|max:255|unique:inventory_counts,count_reference',
            'warehouse_id'    => 'required|exists:warehouses,id',
            'status'          => 'sometimes|in:draft,in_progress,completed,approved,cancelled',
            'count_date'      => 'sometimes|date',
            'notes'           => 'nullable|string',
            'metadata'        => 'nullable|array',
        ];
    }
}
