<?php
// app/Http/Resources/ProductLaunchResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductLaunchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'subtitle'    => $this->subtitle,
            'description' => $this->description,
            'cta_text'    => $this->cta_text,
            'cta_url'     => $this->cta_url,
            'hero_image'  => storage_url($this->hero_image_path),
            'launches_at' => $this->launches_at?->toISOString(),
            'ends_at'     => $this->ends_at?->toISOString(),
            'status'      => $this->status,
            'is_live'     => $this->is_live,
            'product'     => $this->whenLoaded('product', function () {
                return new ProductResource($this->product);
            }),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}