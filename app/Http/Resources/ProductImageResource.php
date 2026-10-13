<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'product_item_id' => $this->product_item_id,

            'image_url' => $this->image_path
                ? Storage::disk('s3')->url($this->image_path)
                : null,


            'thumbnail_url' => $this->thumbnail_path
                ? Storage::disk('s3')->url($this->thumbnail_path)
                : null,

            'medium_url' => $this->medium_path
                ? Storage::disk('s3')->url($this->medium_path)
                : null,

            'large_url' => $this->large_path
                ? Storage::disk('s3')->url($this->large_path)
                : null,

            'file_name'       => $this->file_name,
            'mime_type'       => $this->mime_type,
            'file_size'       => $this->file_size,
            'sort_order'      => $this->sort_order,
            'alt_text'        => $this->alt_text,
            'title'           => $this->title,
            'caption'         => $this->caption,
            'attributes' => empty($this->attributes)
                ? new \stdClass()
                : $this->attributes,
            'is_primary'      => $this->is_primary,
            'is_active'       => $this->is_active,
            'created_at'      => $this->created_at?->toISOString(),
            'updated_at'      => $this->updated_at?->toISOString(),
        ];
    }
}
