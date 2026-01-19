<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\CustomerNote\StoreCustomerNoteRequest;
use App\Http\Requests\V1\CustomerNote\UpdateCustomerNoteRequest;
use App\Models\CustomerNote;

class CustomerNoteController extends Controller
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
    public function store(StoreCustomerNoteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerNote $customerNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerNoteRequest $request, CustomerNote $customerNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerNote $customerNote)
    {
        //
    }
}
