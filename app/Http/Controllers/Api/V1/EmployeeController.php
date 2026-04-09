<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Employee\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\Employee\UpdateEmployeeRequest;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['user', 'branch.company'])->get();

        return $this->successResponse(EmployeeResource::collection($employees), __('lang.Employees retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Auto-assign user and branch if missing
        if (empty($data['user_id'])) {
            $data['user_id'] = $user->id;
        }

        if (empty($data['branch_id'])) {
            $data['branch_id'] = $user->branch_id;
        }

        $employee = Employee::create($data);

        return $this->successResponse(new EmployeeResource($employee->load(['branch.company'])), __('lang.Employee created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $this->successResponse(new EmployeeResource($employee->load(['branch.company', 'salaries'])), __('lang.Employee retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return $this->successResponse(new EmployeeResource($employee), __('lang.Employee updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return $this->successResponse(null, __('lang.Employee deleted successfully'));
    }
}
