<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\InventoryTransaction\StoreInventoryTransactionRequest;
use App\Http\Requests\V1\InventoryTransaction\UpdateInventoryTransactionRequest;
use App\Models\InventoryTransaction;

class InventoryTransactionController extends Controller
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
    public function store(StoreInventoryTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryTransactionRequest $request, InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        //
    }
}
