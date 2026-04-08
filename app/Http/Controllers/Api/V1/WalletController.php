<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Wallet\StoreWalletRequest;
use App\Http\Requests\Api\V1\Wallet\UpdateWalletRequest;
use App\Http\Resources\Api\V1\WalletResource;
use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::latest()->paginate(10);

        return $this->paginatedResponse($wallets, __('lang.Wallets retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        $wallet = Wallet::create($request->validated());

        return $this->successResponse(new WalletResource($wallet), __('lang.Wallet created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        return $this->successResponse(new WalletResource($wallet), __('lang.Wallet retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $wallet->update($request->validated());

        return $this->successResponse(new WalletResource($wallet), __('lang.Wallet updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return $this->successResponse(null, __('lang.Wallet deleted successfully'));
    }
}
