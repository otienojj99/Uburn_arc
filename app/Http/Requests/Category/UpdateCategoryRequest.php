<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')->id ?? $this->category;
        return [
            'name'        => 'sometimes|string|max:255',
            'slug'        => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                // Prevent setting parent to itself or its own descendant (optional but good)
                function ($attribute, $value, $fail) use ($categoryId) {
                    if ($value == $categoryId) {
                        $fail('A category cannot be its own parent.');
                    }
                }
            ],

            'is_active'   => 'sometimes|boolean',
            'sort_order'  => 'sometimes|integer',
            'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
            'metadata'    => 'nullable|array',
        ];
    }
}
