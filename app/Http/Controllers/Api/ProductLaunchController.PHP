<?php
// app/Http/Controllers/Api/ProductLaunchController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductLaunchResource;
use App\Services\ProductLaunchService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductLaunchController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
         protected ProductLaunchService $launchService
    ){}

    public function current ()
    {
        $launch = $this->launchService->getCurrent();

        return $this->successResponse(
            $launch ? new ProductLaunchResource($launch) : null
        );
    }

    /**
     * Public — upcoming launches.
     */
    public function upcoming(Request $request)
    {
        $limit = (int) $request->input('limit', 5);

        $launches = $this->launchService->getUpcoming($limit);

        return $this->successResponse(
            ProductLaunchResource::collection($launches)
        );
    }
}