<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
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
            // 'product_item_id' => 'required|exists:product_items,id',
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'sort_order'      => 'sometimes|integer',
            'alt_text'        => 'nullable|string|max:255',
            'title'           => 'nullable|string|max:255',
            'caption'         => 'nullable|string|max:500',
            'is_primary'      => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
            'attributes'         => 'nullable|array',
            'attributes.color'   => 'nullable|string|max:50',
            'attributes.view'    => 'nullable|string|max:50',
            'attributes.material' => 'nullable|string|max:50',
        ];
    }
}
