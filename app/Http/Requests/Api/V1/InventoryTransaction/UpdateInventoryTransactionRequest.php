<?php

namespace App\Http\Requests\Api\V1\InventoryTransaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryTransactionRequest extends FormRequest
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
            'product_id' => ['sometimes', 'required', 'exists:products,id'],
            'branch_id' => ['sometimes', 'required', 'exists:branches,id'],
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'reference_id' => ['nullable'],
            'type' => ['sometimes', 'required', 'in:in,out,transfer,adjustment'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
