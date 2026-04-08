<?php

namespace App\Http\Requests\Api\V1\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
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
            'quantity' => ['sometimes', 'required', 'numeric'],
            'min_quantity' => ['sometimes', 'required', 'numeric'],
            'max_quantity' => ['sometimes', 'required', 'numeric'],
        ];
    }
}
