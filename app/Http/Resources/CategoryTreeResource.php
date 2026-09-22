<?php


// app/Http/Resources/CategoryTreeResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryTreeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'image' => storage_url($this->image),
            'full_path' => $this->full_path,
            'children' => CategoryTreeResource::collection(
                $this->whenLoaded('descendants')
            ),
        ];
    }
}
