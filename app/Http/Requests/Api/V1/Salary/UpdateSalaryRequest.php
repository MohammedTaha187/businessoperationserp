<?php

namespace App\Http\Requests\Api\V1\Salary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRequest extends FormRequest
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
            'employee_id' => ['sometimes', 'required', 'exists:employees,id'],
            'month' => ['sometimes', 'required', 'date'],
            'year' => ['sometimes', 'required', 'numeric'],
            'basic' => ['sometimes', 'required', 'numeric'],
            'bonuses' => ['sometimes', 'required', 'numeric'],
            'deductions' => ['sometimes', 'required', 'numeric'],
            'net' => ['sometimes', 'required', 'numeric'],
            'status' => ['sometimes', 'required', 'in:pending,paid'],
        ];
    }
}
