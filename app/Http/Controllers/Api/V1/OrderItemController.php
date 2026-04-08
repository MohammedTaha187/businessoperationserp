<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OrderItem\StoreOrderItemRequest;
use App\Http\Requests\Api\V1\OrderItem\UpdateOrderItemRequest;
use App\Http\Resources\Api\V1\OrderItemResource;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderItems = OrderItem::latest()->paginate(10);

        return $this->paginatedResponse($orderItems, __('lang.Order Items retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderItemRequest $request)
    {
        $orderItem = OrderItem::create($request->validated());

        return $this->successResponse(new OrderItemResource($orderItem), __('lang.Order Item created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        return $this->successResponse(new OrderItemResource($orderItem), __('lang.Order Item retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem)
    {
        $orderItem->update($request->validated());

        return $this->successResponse(new OrderItemResource($orderItem), __('lang.Order Item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        return $this->successResponse(null, __('lang.Order Item deleted successfully'));
    }
}
