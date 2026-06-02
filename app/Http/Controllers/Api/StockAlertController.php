<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAlert\StoreStockAlertRequest;
use App\Http\Requests\StockAlert\UpdateStockAlertRequest;
use App\Http\Resources\StockAlertResource;
use App\Models\StockAlert;
use App\Services\StockAlertService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{

    use ApiResponseTrait;

    public function __construct(
        protected StockAlertService $alertService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filters = $request->only([
            'product_item_id',
            'warehouse_id',
            'alert_type',
            'severity',
            'is_active',
            'is_resolved',
            'trashed'
        ]);
        $perPage = $request->input('per_page', 15);

        $alerts = $this->alertService->getPaginated($filters, $perPage);

        return $this->successResponse(
            StockAlertResource::collection($alerts)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockAlertRequest $request)
    {
        //
        $alert = $this->alertService->create($request->validated());

        return $this->successResponse(
            new StockAlertResource($alert),
            'Stock alert created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAlert $stock_alert)
    {
        //
        $stock_alert->load(['productItem', 'warehouse', 'resolver']);
        return $this->successResponse(new StockAlertResource($stock_alert));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStockAlertRequest $request, StockAlert $stock_alert)
    {
        //
        $alert = $this->alertService->update($stock_alert, $request->validated());

        return $this->successResponse(
            new StockAlertResource($alert),
            'Stock alert updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAlert $stock_alert)
    {
        //

        $this->alertService->delete($stock_alert);
        return $this->successResponse(null, 'Stock alert deleted successfully.');
    }

    public function restore($id)
    {
        try {
            $this->alertService->restore($id);
            return $this->successResponse(null, 'Stock alert restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function resolve(Request $request, StockAlert $stock_alert)
    {
        $data = $request->validate([
            'resolution_notes' => 'nullable|string',
        ]);
        $alert = $this->alertService->resolve($stock_alert, $data);
        return $this->successResponse(
            new StockAlertResource($alert),
            'Stock alert resolved successfully.'
        );
    }
}
