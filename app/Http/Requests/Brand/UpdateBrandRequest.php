<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateBrandRequest extends FormRequest
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
        $brandId = $this->route('brand')->id ?? $this->brand;
        return [
            'name'            => 'sometimes|string|max:255',
            'slug'            => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('brands', 'slug')->ignore($brandId),
            ],
            'description'     => 'nullable|string',
            'logo'            => 'nullable|string|max:255',
            'cover_image'     => 'nullable|string|max:255',
            'website'         => 'nullable|string|max:255|url',
            'email'           => 'nullable|email|max:255',
            'phone'           => 'nullable|string|max:50',
            'is_active'       => 'sometimes|boolean',
            'is_featured'     => 'sometimes|boolean',
            'sort_order'      => 'sometimes|integer',
            'meta_title'      => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'   => 'nullable|string|max:500',
            'metadata'        => 'nullable|array',
        ];
    }
}
