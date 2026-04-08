<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreCustomerRequest;
use App\Http\Requests\Api\V1\Customer\UpdateCustomerRequest;
use App\Http\Resources\Api\V1\CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);

        return $this->paginatedResponse($customers, __('lang.Customers retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return $this->successResponse(new CustomerResource($customer), __('lang.Customer created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return $this->successResponse(new CustomerResource($customer), __('lang.Customer retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return $this->successResponse(new CustomerResource($customer), __('lang.Customer updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return $this->successResponse(null, __('lang.Customer deleted successfully'));
    }
}
