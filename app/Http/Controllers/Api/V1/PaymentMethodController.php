<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\Api\V1\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\Api\V1\PaymentMethodResource;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);

        return $this->paginatedResponse($paymentMethods, __('lang.Payment Methods retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::create($request->validated());

        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('lang.Payment Method created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('lang.Payment Method retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());

        return $this->successResponse(new PaymentMethodResource($paymentMethod), __('lang.Payment Method updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return $this->successResponse(null, __('lang.Payment Method deleted successfully'));
    }
}
