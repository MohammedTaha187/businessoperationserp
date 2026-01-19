<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\SalesOrderItem\StoreSalesOrderItemRequest;
use App\Http\Requests\V1\SalesOrderItem\UpdateSalesOrderItemRequest;
use App\Models\SalesOrderItem;

class SalesOrderItemController extends Controller
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
    public function store(StoreSalesOrderItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrderItem $salesOrderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesOrderItemRequest $request, SalesOrderItem $salesOrderItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrderItem $salesOrderItem)
    {
        //
    }
}
