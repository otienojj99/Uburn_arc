<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductItem;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductService $productService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        \Log::info('Index method called');

        $filters = $request->only([
            'search',
            'category_id',
            'brand_id',
            'is_active',
            'min_price',
            'max_price',
            'low_stock',
            'out_of_stock',
            'trashed'
        ]);

        $perPage = $request->input('per_page', 15);

        $products = $this->productService->getPaginated($filters, $perPage);

        return $this->successResponse(
            ProductResource::collection($products)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Remove fields that are empty strings to allow model defaults
        $data = array_filter($data, function ($value) {
            return $value !== '' && $value !== null;
        });

        $product = $this->productService->create($data);

        return $this->successResponse(
            new ProductResource($product),
            'Product created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductItem $product)
    {
        \Log::info('Show method called for product ID: ' . $product->id);

        $product->load([
            'category',
            'brand',
            'primarySupplier',
            'primaryWarehouse',
            'featuredImage',
            'images',
            'variants',
            'barcodes',
            'stockAlerts'
        ]);


        \Log::info('Product item loaded with relationships', [
            'category' => $product->category,
            'brand' => $product->brand,
            'primarySupplier' => $product->primarySupplier,
            'primaryWarehouse' => $product->primaryWarehouse,
            'images' => $product->images,
            'variants' => $product->variants,
            'barcodes' => $product->barcodes,
            'stockAlerts' => $product->stockAlerts
        ]);

        \Log::info('Product item featured image path', [
            'id' => $product->id,
            'name' => $product->name,
            'product' => $product,
            'featured_image' => $product->featured_image
        ]);
        return $this->successResponse(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, ProductItem $product)
    {
        $product = $this->productService->update($product, $request->validated());

        return $this->successResponse(
            new ProductResource($product),
            'Product updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductItem $product)
    {
        try {
            $this->productService->delete($product);
            return $this->successResponse(null, 'Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Restore a soft-deleted product.
     */

    public function restore($id)
    {
        try {
            $this->productService->restore($id);
            return $this->successResponse(null, 'Product restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Toggle product active status.
     */
    public function toggleActive(ProductItem $product)
    {
        $this->productService->toggleActive($product);
        return $this->successResponse(
            new ProductResource($product),
            'Product status toggled successfully.'
        );
    }
}
