<?php

namespace App\Http\Requests\Product;

use App\Models\ProductItem;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'sku'                   => 'required|string|max:255|unique:product_items,sku',
            'name'                  => 'required|string|max:255',
            'slug'                  => 'nullable|string|max:255|unique:product_items,slug',
            'description'           => 'nullable|string',
            'short_description'     => 'nullable|string|max:500',
            'cost_price'            => 'nullable|numeric|min:0',
            'selling_price'         => 'required|numeric|min:0',
            'compare_price'         => 'nullable|numeric|min:0',
            'wholesale_price'       => 'nullable|numeric|min:0',
            'current_stock'         => 'nullable|integer|min:0',
            'minimum_stock'         => 'nullable|integer|min:0',
            'maximum_stock'         => 'nullable|integer|min:0',
            'reorder_point'         => 'nullable|integer|min:0',
            'safety_stock'          => 'nullable|integer|min:0',
            'category_id'           => 'nullable|exists:categories,id',
            'brand_id'              => 'nullable|exists:brands,id',
            'primary_supplier_id'   => 'nullable|exists:suppliers,id',
            'primary_warehouse_id'  => 'nullable|exists:warehouses,id',
            'unit'                  => 'nullable|string|max:50',
            'weight'                => 'nullable|numeric|min:0',
            'length'                => 'nullable|numeric|min:0',
            'width'                 => 'nullable|numeric|min:0',
            'height'                => 'nullable|numeric|min:0',
            'is_active'             => 'sometimes|boolean',
            'is_tracked'            => 'sometimes|boolean',
            'allow_backorder'       => 'sometimes|boolean',
            'is_featured'           => 'sometimes|boolean',
            'is_new'                => 'sometimes|boolean',
            'is_on_sale'            => 'sometimes|boolean',
            'tax_class'             => 'nullable|string|max:100',
            'hs_code'               => 'nullable|string|max:50',
            'country_of_origin'     => 'nullable|string|max:100',
            'barcode'               => 'nullable|string|max:255|unique:product_items,barcode',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string|max:500',
            'meta_keywords'         => 'nullable|string|max:500',
            'featured_image'        => 'nullable|string|max:255',
            'gallery_images'        => 'nullable|array',
            'gallery_images.*'      => 'string|max:255',
            'attributes'            => 'nullable|array',
            'audience'              => 'nullable|array',
            'audience.*'            => 'string|in:' . implode(',', ProductItem::AUDIENCES),
            'variations'            => 'nullable|array',
        ];
    }
}
