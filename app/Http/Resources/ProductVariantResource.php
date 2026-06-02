<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
             'id'               => $this->id,
            'product_item_id'  => $this->product_item_id,
            'sku'              => $this->sku,
            'name'             => $this->name,
            'attributes'       => $this->attributes,
            'price_adjustment' => $this->price_adjustment,
            'price'            => $this->price,
            'final_price'      => $this->final_price, // accessor from model
            'stock'            => $this->stock,
            'image'            => $this->image,
            'is_active'        => $this->is_active,
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
