<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductVariantService $variantService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ?int $productId = null)
    {
        if ($productId) {

            $variants = $this->variantService->getForProduct($productId);

            return $this->successResponse(
                ProductVariantResource::collection($variants)
            );
        }

        $filters = $request->only([
            'product_item_id',
            'search',
            'is_active',
            'trashed'
        ]);

        $perPage = $request->input('per_page', 15);

        $variants = $this->variantService->getPaginated(
            $filters,
            $perPage
        );

        return $this->successResponse(
            ProductVariantResource::collection($variants)
                ->response()
                ->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreProductVariantRequest $request,
        int $productId
    ) {
        $data = $request->validated();

        $data['product_item_id'] = $productId;

        $variant = $this->variantService->create($data);

        return $this->successResponse(
            new ProductVariantResource($variant),
            'Variant created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(mixed $productVariant)
    {
        $productVariant = $this->resolveVariant($productVariant);
        $productVariant->load('productItem');
        return $this->successResponse(new ProductVariantResource($productVariant));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, mixed $productVariant)
    {
        $productVariant = $this->resolveVariant($productVariant);
        $variant = $this->variantService->update($productVariant, $request->validated());

        return $this->successResponse(
            new ProductVariantResource($variant),
            'Variant updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(mixed $productVariant)
    {
        $productVariant = $this->resolveVariant($productVariant);

        // Check if the variant is associated with any orders
        \Log::info('Checking if variant is associated with any orders', ['variant_id' => $productVariant->id]);
        $this->variantService->delete($productVariant);
        // checking if product is deleted successfully
        \Log::info('Variant deleted successfully', ['variant_id' => $productVariant->id]);
        return $this->successResponse(null, 'Variant deleted successfully.');
    }


    public function restore($id)
    {
        try {
            $this->variantService->restore($id);
            return $this->successResponse(null, 'Variant restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function toggleActive(mixed $productVariant)
    {
        $productVariant = $this->resolveVariant($productVariant);
        $this->variantService->toggleActive($productVariant);
        return $this->successResponse(
            new ProductVariantResource($productVariant),
            'Variant status toggled successfully.'
        );
    }

    private function resolveVariant(mixed $productVariant): ProductVariant
    {
        return $productVariant instanceof ProductVariant
            ? $productVariant
            : ProductVariant::findOrFail($productVariant);
    }
}
