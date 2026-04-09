<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Order\StoreOrderRequest;
use App\Http\Requests\V1\Order\UpdateOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Services\Api\V1\OrderService;
use App\Models\Order;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer', 'branch', 'user'])->latest()->paginate(10);

        return $this->paginatedResponse($orders, __('lang.Orders retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());

        return $this->successResponse(new OrderResource($order->load(['customer', 'branch', 'user'])), __('lang.Order created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $this->successResponse(new OrderResource($order->load(['customer', 'branch', 'user', 'items.product'])), __('lang.Order retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return $this->successResponse(new OrderResource($order), __('lang.Order updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return $this->successResponse(null, __('lang.Order deleted successfully'));
    }
}
