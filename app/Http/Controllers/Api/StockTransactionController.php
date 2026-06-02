<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockTransaction\StoreManualAdjustmentRequest;
use App\Http\Resources\StockTransactionResource;
use App\Models\StockTransaction;
use App\Services\StockTransactionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected StockTransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only([
            'product_item_id',
            'warehouse_id',
            'type',
            'user_id',
            'from_date',
            'to_date',
            'reference_type',
            'reference_id',
            'trashed'
        ]);
        $perPage = $request->input('per_page', 15);

        $transactions = $this->transactionService->getPaginated($filters, $perPage);

        return $this->successResponse(
            StockTransactionResource::collection($transactions)->response()->getData(true)
        );
    }

    public function store(StoreManualAdjustmentRequest $request)
    {
        try {
            $transaction = $this->transactionService->createManualAdjustment($request->validated());
            return $this->successResponse(
                new StockTransactionResource($transaction),
                'Manual adjustment created successfully.',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show(StockTransaction $stock_transaction)
    {
        $stock_transaction->load(['productItem', 'warehouse', 'user']);
        return $this->successResponse(new StockTransactionResource($stock_transaction));
    }

    // No update method – transactions are immutable

    public function destroy(StockTransaction $stock_transaction)
    {
        // Only allow deletion of adjustment transactions? Or just soft delete.
        $this->transactionService->delete($stock_transaction);
        return $this->successResponse(null, 'Transaction deleted successfully.');
    }

    public function restore($id)
    {
        try {
            $this->transactionService->restore($id);
            return $this->successResponse(null, 'Transaction restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
