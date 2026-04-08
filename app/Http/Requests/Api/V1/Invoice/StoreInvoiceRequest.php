<?php

namespace App\Http\Requests\Api\V1\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'order_id' => ['required', 'exists:orders,id'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:unpaid,partial,paid'],
            'due_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric'],
            'paid_amount' => ['required', 'numeric'],
        ];
    }
}
