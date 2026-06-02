<?php

namespace App\Http\Resources;

use App\Http\Resources\InventoryCountResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryCountItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'inventory_count_id'   => $this->inventory_count_id,
            'inventory_count'      => new InventoryCountResource($this->whenLoaded('inventoryCount')),
            'product_item_id'      => $this->product_item_id,
            'product'              => new ProductResource($this->whenLoaded('productItem')),
            'barcode_id'           => $this->barcode_id,
            'barcode'              => new BarcodeResource($this->whenLoaded('barcode')),
            'system_quantity'      => $this->system_quantity,
            'counted_quantity'     => $this->counted_quantity,
            'difference'           => $this->difference,
            'status'               => $this->status,
            'counted_by'           => $this->counter?->name,
            'verified_by'          => $this->verifier?->name,
            'notes'                => $this->notes,
            'metadata'             => $this->metadata,
            'is_counted'           => $this->is_counted,
            'is_verified'          => $this->is_verified,
            'is_adjusted'          => $this->is_adjusted,
            'discrepancy_type'     => $this->discrepancy_type,
            'created_at'           => $this->created_at?->toISOString(),
            'updated_at'           => $this->updated_at?->toISOString(),
        ];
    }
}
