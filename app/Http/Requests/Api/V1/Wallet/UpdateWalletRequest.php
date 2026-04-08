<?php

namespace App\Http\Requests\Api\V1\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
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
            'branch_id' => ['sometimes', 'required', 'exists:branches,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'balance' => ['sometimes', 'required', 'numeric'],
            'currency' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
