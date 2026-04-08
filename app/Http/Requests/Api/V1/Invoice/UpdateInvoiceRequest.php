<?php

namespace App\Http\Requests\Api\V1\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'order_id' => ['sometimes', 'required', 'exists:orders,id'],
            'invoice_number' => ['sometimes', 'required', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'in:unpaid,partial,paid'],
            'due_date' => ['sometimes', 'required', 'date'],
            'total_amount' => ['sometimes', 'required', 'numeric'],
            'paid_amount' => ['sometimes', 'required', 'numeric'],
        ];
    }
}
