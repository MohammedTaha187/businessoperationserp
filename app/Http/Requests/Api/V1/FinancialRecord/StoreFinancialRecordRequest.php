<?php

namespace App\Http\Requests\Api\V1\FinancialRecord;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancialRecordRequest extends FormRequest
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
            'company_id' => ['sometimes', 'nullable', 'exists:companies,id'],
            'wallet_id' => ['sometimes', 'nullable', 'exists:wallets,id'],
            'payment_method_id' => ['sometimes', 'nullable', 'exists:payment_methods,id'],
            'reference_id' => ['required'],
            'reference_type' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric'],
            'description' => ['required', 'string'],
        ];
    }
}
