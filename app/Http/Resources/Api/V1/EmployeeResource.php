<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->user?->name,
            'email' => $this->user?->email,
            'phone' => $this->user?->phone,
            'branch_id' => $this->branch_id,
            'branch_name' => $this->branch?->name,
            'company_name' => $this->branch?->company?->name,
            'national_id' => $this->national_id,
            'department' => $this->department,
            'position' => $this->position,
            'hire_date' => $this->hire_date,
            'join_date' => $this->hire_date,
            'basic_salary' => $this->basic_salary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
