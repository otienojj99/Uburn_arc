<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\DiscountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected DiscountService $discountService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filters = $request->only(['search', 'type', 'status', 'is_active', 'applies_to', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $discounts = $this->discountService->getPaginated($filters, $perPage);

        return $this->successResponse(
            DiscountResource::collection($discounts)->response()->getData(true)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountRequest $request)
    {
        //
        $discount = $this->discountService->create($request->validated());

        return $this->successResponse(
            new DiscountResource($discount),
            'Discount created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        //
        $discount->load('freeProduct');
        return $this->successResponse(new DiscountResource($discount));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        //
        $discount = $this->discountService->update($discount, $request->validated());

        return $this->successResponse(
            new DiscountResource($discount),
            'Discount updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        //
        $this->discountService->delete($discount);
        return $this->successResponse(null, 'Discount deleted successfully.');
    }

    public function restore($id)
    {
        try {
            $this->discountService->restore($id);
            return $this->successResponse(null, 'Discount restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function toggleActive(Discount $discount)
    {
        $this->discountService->toggleActive($discount);
        return $this->successResponse(
            new DiscountResource($discount),
            'Discount status toggled successfully.'
        );
    }
}
