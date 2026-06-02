<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;


class BrandController extends Controller
{

    use ApiResponseTrait;

    public function __construct(protected BrandService $brandService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'is_featured', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $brands = $this->brandService->getPaginated($filters, $perPage);

        return $this->successResponse(
            BrandResource::collection($brands)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();
        // Auth IDs are handled by HasUserAudit trait
        $brand = $this->brandService->create($data);

        return $this->successResponse(
            new BrandResource($brand),
            'Brand created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $brand->load('products'); // optional, if you want to include product count
        return $this->successResponse(new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand = $this->brandService->update($brand, $request->validated());

        return $this->successResponse(
            new BrandResource($brand),
            'Brand updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $this->brandService->delete($brand);
            return $this->successResponse(null, 'Brand deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Restore a soft-deleted brand.
     */
    public function restore($id)
    {
        try {
            $this->brandService->restore($id);
            return $this->successResponse(null, 'Brand restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Toggle brand active status.
     */
    public function toggleActive(Brand $brand)
    {
        $this->brandService->toggleActive($brand);
        return $this->successResponse(
            new BrandResource($brand),
            'Brand status toggled successfully.'
        );
    }
}
