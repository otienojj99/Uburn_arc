<?php

namespace App\Http\Requests\InventoryCount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryCountRequest extends FormRequest
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
        $countId = $this->route('inventory_count')->id ?? $this->inventory_count;
        return [
            //
            'count_reference' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('inventory_counts', 'count_reference')->ignore($countId),
            ],
            'warehouse_id'    => 'sometimes|exists:warehouses,id',
            'status'          => 'sometimes|in:draft,in_progress,completed,approved,cancelled',
            'count_date'      => 'sometimes|date',
            'notes'           => 'nullable|string',
            'metadata'        => 'nullable|array',
        ];
    }
}
