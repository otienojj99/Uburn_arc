<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductCollectionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductCollectionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProductCollectionService $collectionService
    ) {}

    public function newArrivals(Request $request)
    {
        $limit = $request->input('limit', 10);
        $products = $this->collectionService->getNewArrivals($limit);
        return $this->successResponse(ProductResource::collection($products));
    }

    public function  bestSellers(Request $request)
    {
        $limit = $request->input('limit', 10);
        $products = $this->collectionService->getBestSellers($limit);
        return $this->successResponse(ProductResource::collection($products));
    }

    public function trending(Request $request)
    {
        $limit = $request->input('limit', 10);
        $products = $this->collectionService->getTrending($limit);
        return $this->successResponse(ProductResource::collection($products));
    }

    public function featured(Request $request)
    {
        $limit = $request->input('limit', 10);
        $products = $this->collectionService->getFeatured($limit);
        return $this->successResponse(ProductResource::collection($products));
    }
}
