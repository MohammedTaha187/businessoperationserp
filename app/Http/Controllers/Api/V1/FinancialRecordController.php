<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\FinancialRecord\StoreFinancialRecordRequest;
use App\Http\Requests\Api\V1\FinancialRecord\UpdateFinancialRecordRequest;
use App\Http\Resources\Api\V1\FinancialRecordResource;
use App\Models\FinancialRecord;

class FinancialRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financialRecords = FinancialRecord::with(['wallet', 'paymentMethod'])->latest()->paginate(10);

        return $this->paginatedResponse($financialRecords, __('lang.Financial Records retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinancialRecordRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Auto-inject missing mandatory fields
        if (empty($data['company_id'])) {
            $data['company_id'] = $user->company_id;
        }

        if (empty($data['wallet_id'])) {
            $data['wallet_id'] = \App\Models\Wallet::where('company_id', $data['company_id'])->first()?->id;
        }

        if (empty($data['payment_method_id'])) {
            $data['payment_method_id'] = \App\Models\PaymentMethod::where('company_id', $data['company_id'])->first()?->id;
        }

        $financialRecord = FinancialRecord::create($data);

        return $this->successResponse(new FinancialRecordResource($financialRecord), __('lang.Financial Record created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FinancialRecord $financialRecord)
    {
        return $this->successResponse(new FinancialRecordResource($financialRecord), __('lang.Financial Record retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinancialRecordRequest $request, FinancialRecord $financialRecord)
    {
        $financialRecord->update($request->validated());

        return $this->successResponse(new FinancialRecordResource($financialRecord), __('lang.Financial Record updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();

        return $this->successResponse(null, __('lang.Financial Record deleted successfully'));
    }
}
