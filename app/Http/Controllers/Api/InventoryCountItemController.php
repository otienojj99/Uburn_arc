<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCountItem\StoreInventoryCountItemRequest;
use App\Http\Requests\InventoryCountItem\UpdateInventoryCountItemRequest;
use App\Http\Resources\InventoryCountItemResource;
use App\Models\InventoryCountItem;
use App\Services\InventoryCountItemService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryCountItemController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected InventoryCountItemService $itemService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['inventory_count_id', 'product_item_id', 'status', 'discrepancy', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $items = $this->itemService->getPaginated($filters, $perPage);

        return $this->successResponse(
            InventoryCountItemResource::collection($items)->response()->getData(true)
        );
    }

    public function store(StoreInventoryCountItemRequest $request)
    {
        \Log::info('InventoryCountController@store reached');
        $item = $this->itemService->create($request->validated());
        \Log::info('InventoryCountController@store created item', ['item_id' => $item->id]);
        return $this->successResponse(
            new InventoryCountItemResource($item),
            'Count item created successfully.',
            201
        );
    }

    public function show(InventoryCountItem $inventory_count_item)
    {
        $inventory_count_item->load(['inventoryCount', 'productItem', 'barcode', 'counter', 'verifier']);
        return $this->successResponse(new InventoryCountItemResource($inventory_count_item));
    }

    public function update(UpdateInventoryCountItemRequest $request, InventoryCountItem $inventory_count_item)
    {
        $item = $this->itemService->update($inventory_count_item, $request->validated());

        return $this->successResponse(
            new InventoryCountItemResource($item),
            'Count item updated successfully.'
        );
    }

    public function destroy(InventoryCountItem $inventory_count_item)
    {
        $this->itemService->delete($inventory_count_item);
        return $this->successResponse(null, 'Count item deleted successfully.');
    }

    public function restore($id)
    {
        try {
            $this->itemService->restore($id);
            return $this->successResponse(null, 'Count item restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function markCounted(Request $request, InventoryCountItem $inventory_count_item)
    {
        $request->validate([
            'counted_quantity' => 'required|integer|min:0',
        ]);
        $item = $this->itemService->markAsCounted(
            $inventory_count_item,
            Auth::id(),
            $request->counted_quantity
        );
        return $this->successResponse(
            new InventoryCountItemResource($item),
            'Item marked as counted.'
        );
    }

    public function verify(InventoryCountItem $inventory_count_item)
    {
        $item = $this->itemService->verify($inventory_count_item, Auth::id());
        return $this->successResponse(
            new InventoryCountItemResource($item),
            'Item verified.'
        );
    }

    public function adjust(InventoryCountItem $inventory_count_item)
    {
        $item = $this->itemService->adjust($inventory_count_item);
        return $this->successResponse(
            new InventoryCountItemResource($item),
            'Item adjusted.'
        );
    }
}
