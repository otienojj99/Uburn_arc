<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Customer::query()
            ->when($filters['search'] ?? null, fn($q, $search) => $q->search($search))
            ->when($filters['customer_type'] ?? null, fn($q, $type) => $q->where('customer_type', $type))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['loyalty_tier'] ?? null, fn($q, $tier) => $q->where('loyalty_tier', $tier))
            ->when($filters['country'] ?? null, fn($q, $country) => $q->where('country', $country))
            ->when($filters['trashed'] ?? false, fn($q) => $q->onlyTrashed());

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data): Customer
    {
        // Generate customer code if not provided
        if (empty($data['customer_code'])) {
            $data['customer_code'] = 'CUST-' . strtoupper(Str::random(8));
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return Customer::create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $customer->update($data);
        return $customer->fresh();
    }

    public function delete(Customer $customer): bool
    {
        // Check if customer has any orders
        if ($customer->sales()->exists()) {
            throw new \Exception('Cannot delete customer with existing orders.');
        }
        return $customer->delete();
    }

    public function restore(int $id): bool
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        return $customer->restore();
    }

    public function changeStatus(Customer $customer, string $status, ?string $blockedReason = null): Customer
    {
        $customer->status = $status;
        if ($status === 'blocked') {
            $customer->blocked_reason = $blockedReason;
        } else {
            $customer->blocked_reason = null;
        }
        $customer->save();
        return $customer->fresh();
    }
}
