<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarcodeResource extends JsonResource
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
            'barcode'          => $this->barcode,
            'product_item_id'  => $this->product_item_id,
            'product'          => new ProductResource($this->whenLoaded('productItem')),
            'type'             => $this->type,
            'format'           => $this->format,
            'is_primary'       => $this->is_primary,
            'is_active'        => $this->is_active,
            'notes'            => $this->notes,
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
