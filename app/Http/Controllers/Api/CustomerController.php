<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'customer_type', 'status', 'loyalty_tier', 'country', 'trashed']);
        $perPage = $request->input('per_page', 15);

        $customers = $this->customerService->getPaginated($filters, $perPage);

        return $this->successResponse(
            CustomerResource::collection($customers)->response()->getData(true)
        );
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerService->create($request->validated());

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer created successfully.',
            201
        );
    }

    public function show(Customer $customer)
    {
        $customer->load('sales');
        return $this->successResponse(new CustomerResource($customer));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer = $this->customerService->update($customer, $request->validated());

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer updated successfully.'
        );
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->delete($customer);
            return $this->successResponse(null, 'Customer deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function restore($id)
    {
        try {
            $this->customerService->restore($id);
            return $this->successResponse(null, 'Customer restored successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function changeStatus(Request $request, Customer $customer)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,blocked,pending',
            'blocked_reason' => 'required_if:status,blocked|nullable|string|max:500',
        ]);

        $customer = $this->customerService->changeStatus(
            $customer,
            $request->status,
            $request->blocked_reason
        );

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer status updated.'
        );
    }
}
