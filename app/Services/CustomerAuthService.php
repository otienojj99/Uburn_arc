<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthService
{
    public function register(array $data): Customer
    {
        // Generate unique customer code
        $data['customer_code'] = 'CUST-' . strtoupper(uniqid());
        $data['password'] = Hash::make($data['password']);
        return Customer::create($data);
    }

    public function login(string $email, string $password)
    {
        $customer = Customer::where('email', $email)->first();
        if (!$customer || !Hash::check($password, $customer->password)) {
            return null;
        }

        if ($customer->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Account is not active.']
            ]);
        }

        $token = $customer->createToken('customer-token')->plainTextToken;
        return [
            'customer' => $customer,
            'token' => $token
        ];
    }
}
