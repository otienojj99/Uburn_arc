<?php

namespace App\Http\Requests\ProductLaunch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductLaunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_item_id' => 'sometimes|nullable|exists:product_items,id',

            'title'       => 'sometimes|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'cta_text'    => 'nullable|string|max:80',
            'cta_url'     => 'nullable|string|max:500',

            'hero_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hero_image_path' => 'nullable|string|max:500',
            'remove_hero_image' => 'sometimes|boolean',

            'launches_at' => 'sometimes|date',
            'ends_at'     => 'nullable|date|after:launches_at',

            'status'    => ['sometimes', Rule::in(['draft', 'scheduled', 'live', 'archived'])],
            'is_active' => 'sometimes|boolean',
            'priority'  => 'nullable|integer|min:0',

            'metadata' => 'nullable|array',
        ];
    }
}