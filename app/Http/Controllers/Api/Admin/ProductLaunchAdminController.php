<?php
// app/Http/Controllers/Api/Admin/ProductLaunchAdminController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductLaunch\StoreProductLaunchRequest;
use App\Http\Requests\ProductLaunch\UpdateProductLaunchRequest;
use App\Http\Resources\ProductLaunchResource;
use App\Models\ProductLaunch;
use App\Services\ProductLaunchService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductLaunchAdminController extends Controller
{
      use ApiResponseTrait;

    public function __construct(
        protected ProductLaunchService $launchService
    ) {}

    public function index (Request $request): JsonResponse
    {
         $filters = $request->only(['search', 'status', 'is_active', 'trashed']);
        $perPage = (int) $request->input('per_page', 15);

        $launches = $this->launchService->getPaginated($filters, $perPage);

        return $this->successResponse(
            ProductLaunchResource::collection($launches)->response()->getData(true)
        );
    }

    public function store(StoreProductLaunchRequest $request): JsonResponse
    {
        $launch = $this->launchService->create($request->validated());
        return $this->successResponse(
            new ProductLaunchResource($launch),
            'Launch created successfully.',
            201
        );
    }

    public function show(ProductLaunch $launch): JsonResponse
    {
        $launch->load(['product.featuredImage']);

        return $this->successResponse(new ProductLaunchResource($launch));
    }

    public function update(
        UpdateProductLaunchRequest $request,
        ProductLaunch $launch
    ): JsonResponse {
        $launch = $this->launchService->update($launch, $request->validated());

        return $this->successResponse(
            new ProductLaunchResource($launch),
            'Launch updated successfully.'
        );
    }

    public function destroy(ProductLaunch $launch): JsonResponse
    {
        $this->launchService->delete($launch);

        return $this->successResponse(null, 'Launch deleted successfully.');
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $launch = $this->launchService->restore($id);

            return $this->successResponse(
                new ProductLaunchResource($launch),
                'Launch restored successfully.'
            );
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function toggleActive(ProductLaunch $launch): JsonResponse
    {
        $launch = $this->launchService->toggleActive($launch);

        return $this->successResponse(
            new ProductLaunchResource($launch),
            'Launch status toggled successfully.'
        );
    }
}