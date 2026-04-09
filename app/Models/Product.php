<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public $translatable = ['name', 'description'];

    /** @use HasFactory<\Database\Factories\V1\ProductFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include products within a price range.
     */
    public function scopePriceBetween($query, ...$prices)
    {
        // Handle both array and comma-separated string from QueryBuilder
        if (count($prices) === 1 && is_string($prices[0])) {
            $prices = explode(',', $prices[0]);
        } elseif (is_array($prices[0])) {
            $prices = $prices[0];
        }

        return $query->whereBetween('price', [
            $prices[0] ?? 0,
            $prices[1] ?? 999999
        ]);
    }
}
