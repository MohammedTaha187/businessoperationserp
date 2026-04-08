<?php

namespace App\Http\Requests\Api\V1\Salary;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'month' => ['required', 'date'],
            'year' => ['required', 'numeric'],
            'basic' => ['required', 'numeric'],
            'bonuses' => ['required', 'numeric'],
            'deductions' => ['required', 'numeric'],
            'net' => ['required', 'numeric'],
            'status' => ['required', 'in:pending,paid'],
        ];
    }
}
