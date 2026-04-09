<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'wallet_id' => $this->wallet_id,
            'wallet_name' => $this->wallet?->name,
            'payment_method_id' => $this->payment_method_id,
            'reference' => $this->reference_no ?? ('JRNL-' . str_pad($this->id, 5, '0', STR_PAD_LEFT)),
            'reference_no' => $this->reference_no,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'status' => $this->status ?? 'completed',
            'date' => $this->created_at?->format('Y-m-d'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
