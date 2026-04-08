<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Opportunity\StoreOpportunityRequest;
use App\Http\Requests\Api\V1\Opportunity\UpdateOpportunityRequest;
use App\Http\Resources\Api\V1\OpportunityResource;
use App\Models\Opportunity;

class OpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opportunities = Opportunity::latest()->paginate(10);

        return $this->paginatedResponse($opportunities, __('lang.Opportunities retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOpportunityRequest $request)
    {
        $opportunity = Opportunity::create($request->validated());

        return $this->successResponse(new OpportunityResource($opportunity), __('lang.Opportunity created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Opportunity $opportunity)
    {
        return $this->successResponse(new OpportunityResource($opportunity), __('lang.Opportunity retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOpportunityRequest $request, Opportunity $opportunity)
    {
        $opportunity->update($request->validated());

        return $this->successResponse(new OpportunityResource($opportunity), __('lang.Opportunity updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return $this->successResponse(null, __('lang.Opportunity deleted successfully'));
    }
}
