<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Branch\StoreBranchRequest;
use App\Http\Requests\Api\V1\Branch\UpdateBranchRequest;
use App\Http\Resources\Api\V1\BranchResource;
use App\Models\Branch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::latest()->paginate(10);

        return $this->paginatedResponse($branches, __('lang.Branches retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->validated());

        return $this->successResponse(new BranchResource($branch), __('lang.Branch created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return $this->successResponse(new BranchResource($branch), __('lang.Branch retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

        return $this->successResponse(new BranchResource($branch), __('lang.Branch updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return $this->successResponse(null, __('lang.Branch deleted successfully'));
    }
}
