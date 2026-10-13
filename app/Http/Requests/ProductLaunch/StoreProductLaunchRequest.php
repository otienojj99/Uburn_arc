<?php


namespace App\Http\Requests\ProductLaunch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductLaunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_item_id' => 'nullable|exists:product_items,id',

            // Marketing content
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'cta_text'    => 'nullable|string|max:80',
            'cta_url'     => 'nullable|string|max:500',

            // Hero image: either a file upload or a pre-existing path/URL string
            'hero_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'hero_image_path' => 'nullable|string|max:500',

            // Timing
            'launches_at' => 'required|date',
            'ends_at'     => 'nullable|date|after:launches_at',

            // Lifecycle
            'status'    => ['nullable', Rule::in(['draft', 'scheduled', 'live', 'archived'])],
            'is_active' => 'sometimes|boolean',
            'priority'  => 'nullable|integer|min:0',

            // Extra
            'metadata' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'product_item_id.exists' => 'The selected product does not exist.',
            'launches_at.required'   => 'A launch date and time is required.',
            'ends_at.after'          => 'The end date must be after the launch date.',
        ];
    }
}