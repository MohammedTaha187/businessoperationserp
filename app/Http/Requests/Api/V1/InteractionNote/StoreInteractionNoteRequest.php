<?php

namespace App\Http\Requests\Api\V1\InteractionNote;

use Illuminate\Foundation\Http\FormRequest;

class StoreInteractionNoteRequest extends FormRequest
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
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'user_id' => ['required', 'exists:users,id'],
            'note' => ['required', 'string'],
            'type' => ['required', 'in:call,email,meeting'],
        ];
    }
}
