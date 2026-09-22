<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        \Log::info('featured_image raw', [
            'value' => $this->featured_image
        ]);
        \Log::info('gallery_images raw', [
            'value' => $this->gallery_images
        ]);
        return [
            'id'                  => $this->id,
            'sku'                 => $this->sku,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'description'         => $this->description,
            'short_description'   => $this->short_description,
            'cost_price'          => $this->cost_price,
            'selling_price'       => $this->selling_price,
            'compare_price'       => $this->compare_price,
            'wholesale_price'     => $this->wholesale_price,
            'current_stock'       => $this->current_stock,
            'minimum_stock'       => $this->minimum_stock,
            'maximum_stock'       => $this->maximum_stock,
            'reorder_point'       => $this->reorder_point,
            'safety_stock'        => $this->safety_stock,
            'category'            => new CategoryResource($this->whenLoaded('category')),
            'brand'               => new BrandResource($this->whenLoaded('brand')),
            'primary_supplier'    => new SupplierResource($this->whenLoaded('primarySupplier')),
            'primary_warehouse'   => new WarehouseResource($this->whenLoaded('primaryWarehouse')),
            'unit'                => $this->unit,
            'weight'              => $this->weight,
            'length'              => $this->length,
            'width'               => $this->width,
            'height'              => $this->height,
            'is_active'           => $this->is_active,
            'is_tracked'          => $this->is_tracked,
            'allow_backorder'     => $this->allow_backorder,
            'is_featured'         => $this->is_featured,
            'is_new'              => $this->is_new,
            'is_on_sale'          => $this->is_on_sale,
            'tax_class'           => $this->tax_class,
            'hs_code'             => $this->hs_code,
            'country_of_origin'   => $this->country_of_origin,
            'barcode'             => $this->barcode,
            'meta_title'          => $this->meta_title,
            'meta_description'    => $this->meta_description,
            'meta_keywords'       => $this->meta_keywords,
            // 'featured_image' => $this->featured_image
            //     ? Storage::disk('s3')->url($this->featured_image)
            //     : null,
            'featured_image' => new ProductImageResource(
                $this->whenLoaded('featuredImage')
            ),

            'gallery_images' => ProductImageResource::collection(
                $this->whenLoaded('images')
            ),
            'views_count'         => $this->views_count,
            'sales_count'         => $this->sales_count,
            'attributes'          => $this->attributes,
            'audience'              => $this->audience ?? [],

            // 👇 NEW: Human-readable labels for UI (e.g. ['Men', 'Women'])
            'audience_labels'       => $this->getAudienceLabels(),

            'variations'          => $this->variations,
            'created_at'          => $this->created_at?->toISOString(),
            'updated_at'          => $this->updated_at?->toISOString(),
            'deleted_at'          => $this->deleted_at?->toISOString(),
        ];
    }

    protected function getAudienceLabels(): array
    {
        $labelsMap = [
            'men'     => 'Men',
            'women'   => 'Women',
            'kids'    => 'Kids',
            'unisex'  => 'Unisex',
        ];

        return collect($this->audience ?? [])
            ->map(fn($value) => $labelsMap[$value] ?? $value)
            ->values()
            ->toArray();
    }
};
