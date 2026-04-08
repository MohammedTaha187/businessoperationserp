<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Company\StoreCompanyRequest;
use App\Http\Requests\Api\V1\Company\UpdateCompanyRequest;
use App\Http\Resources\Api\V1\CompanyResource;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::latest()->paginate(10);

        return $this->paginatedResponse($companies, __('lang.Companies retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return $this->successResponse(new CompanyResource($company), __('lang.Company created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return $this->successResponse(new CompanyResource($company), __('lang.Company retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return $this->successResponse(new CompanyResource($company), __('lang.Company updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return $this->successResponse(null, __('lang.Company deleted successfully'));
    }
}
