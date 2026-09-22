<?php
// app/Http/Controllers/Admin/DiscountAdminController.php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\DiscountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiscountAdminController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected DiscountService $discountService)
    {
       
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'type', 'status', 'is_active', 'applies_to', 'trashed']);
        $perPage = $request->input('per_page', 20);
        $discounts = $this->discountService->getPaginatedDiscounts($filters, $perPage);
        return $this->successResponse(DiscountResource::collection($discounts));
    }

    /**
     * Store a new discount.
     */

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $discount = Discount::create($data);
        DiscountService::clearCaches();

        return $this->successResponse(
            new DiscountResource($discount),
            'Discount created successfully.',
            201
        );
    }

    /**
     * Show a single discount.
     */
    public function show(Discount $discount): JsonResponse
    {
        $discount->load('freeProduct');
        return $this->successResponse(new DiscountResource($discount));
    }

    /**
     * Update an existing discount.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount->update($request->validated());
        DiscountService::clearCaches();

        return $this->successResponse(
            new DiscountResource($discount),
            'Discount updated successfully.'
        );
    }

    /**
     * Delete (soft‑delete) a discount.
     */
    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();
        DiscountService::clearCaches();

        return $this->successResponse(null, 'Discount deleted successfully.');
    }
}
