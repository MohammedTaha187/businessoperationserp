<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Salary\StoreSalaryRequest;
use App\Http\Requests\Api\V1\Salary\UpdateSalaryRequest;
use App\Http\Resources\Api\V1\SalaryResource;
use App\Models\Salary;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaries = Salary::latest()->paginate(10);

        return $this->paginatedResponse($salaries, __('lang.Salaries retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryRequest $request)
    {
        $salary = Salary::create($request->validated());

        return $this->successResponse(new SalaryResource($salary), __('lang.Salary created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        return $this->successResponse(new SalaryResource($salary), __('lang.Salary retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        $salary->update($request->validated());

        return $this->successResponse(new SalaryResource($salary), __('lang.Salary updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();

        return $this->successResponse(null, __('lang.Salary deleted successfully'));
    }
}
