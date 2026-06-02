<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait ApiResponseTrait
{
    /**
     * Success response
     */

    protected function successResponse($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Error response
     */

    protected function errorResponse(?string $message = null, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
