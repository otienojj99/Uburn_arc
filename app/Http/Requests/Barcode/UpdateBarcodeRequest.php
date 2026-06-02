<?php

namespace App\Http\Requests\Barcode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBarcodeRequest extends FormRequest
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
        $barcodeId = $this->route('barcode')->id ?? $this->barcode;
        return [
            //
            'barcode'         => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('barcodes', 'barcode')->ignore($barcodeId),
            ],
            'product_item_id' => 'sometimes|exists:product_items,id',
            'type'            => 'nullable|in:ean13,ean8,upc,code128,code39,qr,datamatrix,custom',
            'format'          => 'nullable|string|max:50',
            'is_primary'      => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
            'notes'           => 'nullable|string',
        ];
    }
}
