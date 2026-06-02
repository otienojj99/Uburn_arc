<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Barcode\StoreBarcodeRequest;
use App\Http\Requests\Barcode\UpdateBarcodeRequest;
use App\Http\Requests\StockTransaction\StoreManualAdjustmentRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\ProductResource;
use App\Models\Barcode;
use App\Services\BarcodeService;
use App\Services\StockTransactionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class BarcodeController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected BarcodeService $barcodeService,
        protected StockTransactionService $stockTransactionService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['product_item_id', 'barcode', 'type', 'is_primary', 'is_active', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $barcodes = $this->barcodeService->getPaginated($filters, $perPage);

        return $this->successResponse(
            BarcodeResource::collection($barcodes)->response()->getData(true)
        );
    }




    public function store(StoreBarcodeRequest $request)
    {
        $barcode = $this->barcodeService->create($request->validated());

        return $this->successResponse(
            new BarcodeResource($barcode),
            'Barcode created successfully.',
            201
        );
    }

    public function show(Barcode $barcode)
    {
        $barcode->load('productItem');
        return $this->successResponse(new BarcodeResource($barcode));
    }

    public function update(UpdateBarcodeRequest $request, Barcode $barcode)
    {
        $barcode = $this->barcodeService->update($barcode, $request->validated());

        return $this->successResponse(
            new BarcodeResource($barcode),
            'Barcode updated successfully.'
        );
    }

    public function destroy(Barcode $barcode)
    {
        $this->barcodeService->delete($barcode);
        return $this->successResponse(null, 'Barcode deleted successfully.');
    }

    public function restore($id)
    {
        try {
            $this->barcodeService->restore($id);
            return $this->successResponse(null, 'Barcode restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function toggleActive(Barcode $barcode)
    {
        $this->barcodeService->toggleActive($barcode);
        return $this->successResponse(
            new BarcodeResource($barcode),
            'Barcode status toggled successfully.'
        );
    }

    /**
     * Look up product by barcode
     * GET /api/barcodes/lookup
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|exists:barcodes,barcode',
        ]);

        $barcode = Barcode::where('barcode', $request->barcode)
            ->with('productItem')
            ->firstOrFail();

        return $this->successResponse([
            'barcode' => $barcode->barcode,
            'product' => new ProductResource($barcode->productItem),
        ], 'Barcode lookup successful');
    }

    /**
     * Process a check‑in / check‑out transaction
     * POST /api/scan
     */

    public function scan(StoreManualAdjustmentRequest $request)
    {
        try {
            \Log::info('Scan request received', [
                'barcode' => $request->barcode,
                'full_request' => $request->all()
            ]);

            $barcodeValue = trim((string) $request->barcode);

            \Log::info('Processed barcode', ['barcode' => $barcodeValue]);

            $barcode = Barcode::where('barcode', $barcodeValue)
                ->with('productItem')
                ->firstOrFail();

            \Log::info('Barcode found', [
                'id' => $barcode->id,
                'product_item_id' => $barcode->product_item_id
            ]);

            $product = $barcode->productItem;

            if (!$product) {
                \Log::error('Product relationship missing', [
                    'barcode_id' => $barcode->id
                ]);

                return response()->json([
                    'message' => 'Product not linked to barcode'
                ], 500);
            }

            $quantity = abs((int) $request->quantity_change);
            $change = $request->action === 'check_in' ? $quantity : -$quantity;
            $type = $request->action === 'check_in' ? 'purchase' : 'sale';
            \Log::info('FINAL VALUES', [
                'quantity' => $request->quantity_change,
                'quantity_change' => $request->quantity_change,
                'action' => $request->action,
                'computed_change' => $change ?? null
            ]);

            $transaction = $this->stockTransactionService->moveStock(
                $product->id,
                $change,
                $type,
                ['notes' => "Scanned barcode: {$barcodeValue}"]
            );

            $product->refresh();

            return $this->successResponse([
                'product'     => new ProductResource($product),
                'transaction' => $transaction,
            ], 'Stock updated successfully');
        } catch (ModelNotFoundException $e) {

            \Log::error('Barcode not found', [
                'input_barcode' => $request->barcode
            ]);

            return response()->json([
                'message' => 'Barcode not found'
            ], 404);
        } catch (\Exception $e) {

            \Log::error('Scan error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
