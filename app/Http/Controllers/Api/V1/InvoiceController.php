<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Api\V1\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\Api\V1\InvoiceResource;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['order.customer', 'order.branch'])->latest()->paginate(10);

        return $this->paginatedResponse($invoices, __('lang.Invoices retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = Invoice::create($request->validated());

        return $this->successResponse(new InvoiceResource($invoice->load('order')), __('lang.Invoice created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return $this->successResponse(new InvoiceResource($invoice->load(['order.customer', 'order.branch', 'order.items.product'])), __('lang.Invoice retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return $this->successResponse(new InvoiceResource($invoice), __('lang.Invoice updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return $this->successResponse(null, __('lang.Invoice deleted successfully'));
    }
}
