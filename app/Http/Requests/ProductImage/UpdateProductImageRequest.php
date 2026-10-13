<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
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
            'sort_order' => 'sometimes|integer',
            'alt_text'   => 'nullable|string|max:255',
            'title'      => 'nullable|string|max:255',
            'caption'    => 'nullable|string|max:500',
            'is_primary' => 'sometimes|boolean',
            'is_active'  => 'sometimes|boolean',
            'attributes'         => 'nullable|array',

        ];
    }
}
