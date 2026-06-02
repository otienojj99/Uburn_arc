<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\AuthServices;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AuthServices $authService
    ) {}

    /**
     * Login user and create token.
     */

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login(
            $request->email,
            $request->password
        );

        return $this->successResponse([
            'user' => new UserResource($data['user']),
            'token' => $data['token']
        ], 'Login successful.');
    }

    /**
     * Logout user (revoke token).
     */

    protected function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Logged out successfully.');
    }

    /**
     * Get authenticated user.
     */

    public function me(Request $request)
    {
        return $this->successResponse(
            new UserResource($request->user()),
            'Authenticated user retrieved.'
        );
    }
}
