<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\PurchaseOrderItem\StorePurchaseOrderItemRequest;
use App\Http\Requests\V1\PurchaseOrderItem\UpdatePurchaseOrderItemRequest;
use App\Models\PurchaseOrderItem;

class PurchaseOrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseOrderItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderItemRequest $request, PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrderItem $purchaseOrderItem)
    {
        //
    }
}
