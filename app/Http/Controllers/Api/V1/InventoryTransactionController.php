<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InventoryTransaction\StoreInventoryTransactionRequest;
use App\Http\Requests\Api\V1\InventoryTransaction\UpdateInventoryTransactionRequest;
use App\Http\Resources\Api\V1\InventoryTransactionResource;
use App\Models\InventoryTransaction;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryTransactions = InventoryTransaction::latest()->paginate(10);

        return $this->paginatedResponse($inventoryTransactions, __('lang.Inventory Transactions retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryTransactionRequest $request)
    {
        $inventoryTransaction = InventoryTransaction::create($request->validated());

        return $this->successResponse(new InventoryTransactionResource($inventoryTransaction), __('lang.Inventory Transaction created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryTransaction $inventoryTransaction)
    {
        return $this->successResponse(new InventoryTransactionResource($inventoryTransaction), __('lang.Inventory Transaction retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryTransactionRequest $request, InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->update($request->validated());

        return $this->successResponse(new InventoryTransactionResource($inventoryTransaction), __('lang.Inventory Transaction updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->delete();

        return $this->successResponse(null, __('lang.Inventory Transaction deleted successfully'));
    }
}
