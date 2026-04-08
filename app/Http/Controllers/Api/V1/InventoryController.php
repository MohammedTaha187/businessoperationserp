<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Inventory\StoreInventoryRequest;
use App\Http\Requests\Api\V1\Inventory\UpdateInventoryRequest;
use App\Http\Resources\Api\V1\InventoryResource;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::latest()->paginate(10);

        return $this->paginatedResponse($inventories, __('lang.Inventories retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->validated());

        return $this->successResponse(new InventoryResource($inventory), __('lang.Inventory created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return $this->successResponse(new InventoryResource($inventory), __('lang.Inventory retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->validated());

        return $this->successResponse(new InventoryResource($inventory), __('lang.Inventory updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return $this->successResponse(null, __('lang.Inventory deleted successfully'));
    }
}
