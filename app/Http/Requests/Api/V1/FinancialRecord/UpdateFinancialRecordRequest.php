<?php

namespace App\Http\Requests\Api\V1\FinancialRecord;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialRecordRequest extends FormRequest
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
            'company_id' => ['sometimes', 'required', 'exists:companies,id'],
            'wallet_id' => ['sometimes', 'required', 'exists:wallets,id'],
            'payment_method_id' => ['sometimes', 'required', 'exists:payment_methods,id'],
            'reference_id' => ['sometimes', 'required'],
            'reference_type' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', 'in:income,expense'],
            'amount' => ['sometimes', 'required', 'numeric'],
            'description' => ['sometimes', 'required', 'string'],
        ];
    }
}
