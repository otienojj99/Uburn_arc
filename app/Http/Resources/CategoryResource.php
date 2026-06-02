<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'parent_id'   => $this->parent_id,
            'parent'      => new CategoryResource($this->whenLoaded('parent')),
            'children'    => CategoryResource::collection($this->whenLoaded('children')),
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
            'image'       => $this->image,
            'full_path'   => $this->image ? Storage::disk('s3')->url($this->image) : null,
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
            'deleted_at'  => $this->deleted_at?->toISOString(),
        ];
    }
}
