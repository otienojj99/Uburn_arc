<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryCountResource extends JsonResource
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
            'count_reference'  => $this->count_reference,
            'warehouse_id'     => $this->warehouse_id,
            'warehouse'        => new WarehouseResource($this->whenLoaded('warehouse')),
            'created_by'       => $this->creator?->name,
            'approved_by'      => $this->approver?->name,
            'status'           => $this->status,
            'count_date'       => $this->count_date?->toDateString(),
            'completed_at'     => $this->completed_at?->toISOString(),
            'notes'            => $this->notes,
            'metadata'         => $this->metadata,
            'items_count'      => $this->whenLoaded('items', fn() => $this->items->count()),
            'items'            => InventoryCountItemResource::collection($this->whenLoaded('items')),
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
