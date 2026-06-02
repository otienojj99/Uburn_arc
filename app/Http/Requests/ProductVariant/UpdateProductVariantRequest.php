<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateProductVariantRequest extends FormRequest
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
        $variantId = $this->route('product_variant')->id ?? $this->product_variant;
        return [
             'sku'              => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($variantId),
            ],
            'name'             => 'sometimes|string|max:255',
            'attributes'       => 'nullable|array',
            'price_adjustment' => 'nullable|numeric|min:0',
            'price'            => 'nullable|numeric|min:0',
            'stock'            => 'nullable|integer|min:0',
            'image'            => 'nullable|string|max:255',
            'is_active'        => 'sometimes|boolean',
        ];
    }
}
