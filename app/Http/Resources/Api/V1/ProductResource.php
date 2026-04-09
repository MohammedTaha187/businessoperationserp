<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id' => $this->category_id,
            'name' => $this->getTranslations('name'),
            'sku' => $this->sku,
            'product_code' => $this->product_code ?? $this->sku,
            'price' => $this->price,
            'unit_price' => $this->price,
            'cost_price' => $this->cost_price,
            'quantity' => $this->stock_quantity ?? 0,
            'stock_quantity' => $this->stock_quantity ?? 0,
            'status' => $this->status ?? (($this->stock_quantity ?? 0) > 0 ? 'in_stock' : 'out_of_stock'),
            'description' => $this->getTranslations('description'),
            'image_url' => $this->getFirstMediaUrl('products'),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
