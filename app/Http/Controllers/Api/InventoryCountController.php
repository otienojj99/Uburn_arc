<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCount\StoreInventoryCountRequest;
use App\Http\Requests\InventoryCount\UpdateInventoryCountRequest;
use App\Http\Resources\InventoryCountResource;
use App\Models\InventoryCount;
use App\Services\InventoryCountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class InventoryCountController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected InventoryCountService $countService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['warehouse_id', 'status', 'from_date', 'to_date', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $counts = $this->countService->getPaginated($filters, $perPage);

        return $this->successResponse(
            InventoryCountResource::collection($counts)->response()->getData(true)
        );
    }

    public function store(StoreInventoryCountRequest $request)
    {
        $data = $request->validated();

        // Auto‑generate count_reference if missing
        if (empty($data['count_reference'])) {
            $data['count_reference'] = 'COUNT-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
        }
        \Log::info('InventoryCountController@store received data', ['data' => $data]);
        $count = $this->countService->create($data);

        return $this->successResponse(
            new InventoryCountResource($count),
            'Inventory count created successfully.',
            201
        );
    }

    public function show(InventoryCount $inventory_count)
    {
        $inventory_count->load(['warehouse', 'creator', 'approver', 'items.productItem']);
        return $this->successResponse(new InventoryCountResource($inventory_count));
    }

    public function update(UpdateInventoryCountRequest $request, InventoryCount $inventory_count)
    {
        $count = $this->countService->update($inventory_count, $request->validated());

        return $this->successResponse(
            new InventoryCountResource($count),
            'Inventory count updated successfully.'
        );
    }

    public function destroy(InventoryCount $inventory_count)
    {
        try {
            $this->countService->delete($inventory_count);
            return $this->successResponse(null, 'Inventory count deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function restore($id)
    {
        try {
            $this->countService->restore($id);
            return $this->successResponse(null, 'Inventory count restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function changeStatus(Request $request, InventoryCount $inventory_count)
    {
        $request->validate(['status' => 'required|in:draft,in_progress,completed,approved,cancelled']);
        $count = $this->countService->changeStatus($inventory_count, $request->status);
        return $this->successResponse(
            new InventoryCountResource($count),
            'Status updated successfully.'
        );
    }
}
