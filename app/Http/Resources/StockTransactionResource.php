<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'product_item_id' => $this->product_item_id,
            'product'         => new ProductResource($this->whenLoaded('productItem')),
            'warehouse_id'    => $this->warehouse_id,
            'warehouse'       => new WarehouseResource($this->whenLoaded('warehouse')),
            'user_id'         => $this->user_id,
            'user'            => $this->user?->name,
            'quantity_change' => $this->quantity_change,
            'stock_before'    => $this->stock_before,
            'stock_after'     => $this->stock_after,
            'type'            => $this->type,
            'reference_type'  => $this->reference_type,
            'reference_id'    => $this->reference_id,
            'batch_number'    => $this->batch_number,
            'expiry_date'     => $this->expiry_date?->toDateString(),
            'reason'          => $this->reason,
            'notes'           => $this->notes,
            'metadata'        => $this->metadata,
            'absolute_quantity' => $this->absolute_quantity,
            'is_increase'     => $this->is_increase,
            'description'     => $this->description,
            'created_at'      => $this->created_at?->toISOString(),
            'updated_at'      => $this->updated_at?->toISOString(),
        ];
    }
}
