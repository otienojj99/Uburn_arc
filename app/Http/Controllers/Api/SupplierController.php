<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Services\SupplierService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected SupplierService $supplierService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'is_preferred', 'country', 'rating', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $suppliers = $this->supplierService->getPaginated($filters, $perPage);

        return $this->successResponse(
            SupplierResource::collection($suppliers)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        // Auth IDs handled by HasUserAudit trait
        $supplier = $this->supplierService->create($data);

        return $this->successResponse(
            new SupplierResource($supplier),
            'Supplier created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('purchases'); // optional
        return $this->successResponse(new SupplierResource($supplier));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $supplier = $this->supplierService->update($supplier, $request->validated());

        return $this->successResponse(
            new SupplierResource($supplier),
            'Supplier updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->supplierService->delete($supplier);
            return $this->successResponse(null, 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Restore a soft-deleted supplier.
     */
    public function restore($id)
    {
        try {
            $this->supplierService->restore($id);
            return $this->successResponse(null, 'Supplier restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Toggle supplier active status.
     */
    public function toggleActive(Supplier $supplier)
    {
        $this->supplierService->toggleActive($supplier);
        return $this->successResponse(
            new SupplierResource($supplier),
            'Supplier status toggled successfully.'
        );
    }

    /**
     * Toggle supplier preferred status.
     */
    public function togglePreferred(Supplier $supplier)
    {
        $this->supplierService->togglePreferred($supplier);
        return $this->successResponse(
            new SupplierResource($supplier),
            'Supplier preferred status toggled successfully.'
        );
    }
}
