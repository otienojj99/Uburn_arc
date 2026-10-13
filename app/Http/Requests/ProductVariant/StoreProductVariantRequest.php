<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
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
            'product_item_id'  => 'required|exists:product_items,id',
            'sku'              => 'required|string|max:255|unique:product_variants,sku',
            'name'             => 'required|string|max:255',
            'attributes'       => 'nullable|array',
            'price_adjustment' => 'nullable|numeric|min:0',
            'final_price'      => 'nullable|numeric|min:0',
            'stock'            => 'nullable|integer|min:0',
            'image_id' => 'nullable|exists:product_images,id',
            'is_active'        => 'sometimes|boolean',
        ];
    }
}
