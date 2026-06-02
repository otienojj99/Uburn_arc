<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'code'          => $this->code,
            'description'   => $this->description,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city'          => $this->city,
            'state'         => $this->state,
            'postal_code'   => $this->postal_code,
            'country'       => $this->country,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'manager_name'  => $this->manager_name,
            'is_active'     => $this->is_active,
            'is_primary'    => $this->is_primary,
            'metadata'      => $this->metadata,
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
            'deleted_at'    => $this->deleted_at?->toISOString(),
        ];
    }
}
