<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Http\Requests\Customer\RegisterCustomerRequest;
use App\Http\Requests\Customer\LoginRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerAuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    use ApiResponseTrait;

    //
    public function __construct(
        protected CustomerAuthService $authService
    ) {}

    public function register(RegisterCustomerRequest $request)
    {
        $customer = $this->authService->register($request->validated());
        $token = $customer->createToken('customer-token')->plainTextToken;

        return $this->successResponse([
            'customer' => new CustomerResource($customer),
            'token' => $token
        ], 'Registration successful', 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);
        if (!$result) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        return $this->successResponse([
            'customer' => new CustomerResource($result['customer']),
            'token' => $result['token']
        ], 'Login successful');
    }

    public function logout(Request $request)
    {
        $request->user('customer')->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    public function me(Request $request)
    {
        return $this->successResponse(new CustomerResource($request->user('customer')));
    }

    public function updateProfile(Request $request)
    {
        $customer = $request->user('customer');
        $data = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:50',
            'alternative_phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:10',
            'marketing_emails' => 'sometimes|boolean',
            'marketing_sms' => 'sometimes|boolean',
        ]);

        $customer->update($data);
        return $this->successResponse(new CustomerResource($customer), 'Profile updated');
    }
}
