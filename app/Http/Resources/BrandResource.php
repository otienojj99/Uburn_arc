<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name'            => $this->name,
            'slug'            => $this->slug,
            'description'     => $this->description,
            'logo'            => $this->logo,
            'cover_image'     => $this->cover_image,
            'website'         => $this->website,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'is_active'       => $this->is_active,
            'is_featured'     => $this->is_featured,
            'sort_order'      => $this->sort_order,
            'meta_title'      => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords'   => $this->meta_keywords,
            'metadata'        => $this->metadata,
            'created_at'      => $this->created_at?->toISOString(),
            'updated_at'      => $this->updated_at?->toISOString(),
            'deleted_at'      => $this->deleted_at?->toISOString(),
        ];
    }
}
