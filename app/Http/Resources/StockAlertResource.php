<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'product_item_id'   => $this->product_item_id,
            'product'           => new ProductResource($this->whenLoaded('productItem')),
            'warehouse_id'      => $this->warehouse_id,
            'warehouse'         => new WarehouseResource($this->whenLoaded('warehouse')),
            'alert_type'        => $this->alert_type,
            'threshold_value'   => $this->threshold_value,
            'current_value'     => $this->current_value,
            'severity'          => $this->severity,
            'is_active'         => $this->is_active,
            'is_resolved'       => $this->is_resolved,
            'resolved_at'       => $this->resolved_at?->toISOString(),
            'resolved_by'       => $this->resolver?->name,
            'resolution_notes'  => $this->resolution_notes,
            'metadata'          => $this->metadata,
            'created_at'        => $this->created_at?->toISOString(),
            'updated_at'        => $this->updated_at?->toISOString(),
        ];
    }
}
