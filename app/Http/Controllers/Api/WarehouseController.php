<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected WarehouseService $warehouseService
    ) {}

    /**
     * Display a listing of warehouses.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'is_primary', 'country', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $warehouses = $this->warehouseService->getPaginated($filters, $perPage);

        return $this->successResponse(
            WarehouseResource::collection($warehouses)->response()->getData(true)
        );
    }

    /**
     * Store a newly created warehouse.
     */
    public function store(StoreWarehouseRequest $request)
    {
        $data = $request->validated();
        $warehouse = $this->warehouseService->create($data);

        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Warehouse created successfully.',
            201
        );
    }

    /**
     * Display the specified warehouse.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load('products', 'stockTransactions');
        return $this->successResponse(new WarehouseResource($warehouse));
    }

    /**
     * Update the specified warehouse.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse = $this->warehouseService->update($warehouse, $request->validated());

        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Warehouse updated successfully.'
        );
    }

    /**
     * Remove the specified warehouse (soft delete).
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            $this->warehouseService->delete($warehouse);
            return $this->successResponse(null, 'Warehouse deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Restore a soft-deleted warehouse.
     */
    public function restore($id)
    {
        try {
            $this->warehouseService->restore($id);
            return $this->successResponse(null, 'Warehouse restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Toggle warehouse active status.
     */
    public function toggleActive(Warehouse $warehouse)
    {
        $this->warehouseService->toggleActive($warehouse);
        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Warehouse status toggled successfully.'
        );
    }

    /**
     * Toggle warehouse primary status.
     */
    public function togglePrimary(Warehouse $warehouse)
    {
        $this->warehouseService->togglePrimary($warehouse);
        return $this->successResponse(
            new WarehouseResource($warehouse),
            'Warehouse primary status toggled successfully.'
        );
    }
}
