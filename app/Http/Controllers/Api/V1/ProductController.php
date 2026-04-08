<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with advanced filtering.
     */
    public function index()
    {
        $products = QueryBuilder::for(Product::class)
            ->with(['category', 'media'])
            ->allowedFilters([
                'name',
                'sku',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('price_between'),
            ])
            ->allowedSorts(['name', 'price', 'created_at'])
            ->latest()
            ->paginate(request()->get('per_page', 10));

        return $this->paginatedResponse($products, __('lang.Products retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $product = Product::create($request->validated());

            if ($request->hasFile('image')) {
                $product->addMediaFromRequest('image')
                    ->toMediaCollection('products');
            }

            return $this->successResponse(
                new ProductResource($product->load(['category', 'media'])),
                __('lang.Product created successfully'),
                201
            );
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->successResponse(new ProductResource($product->load(['category', 'inventories.branch'])), __('lang.Product retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $this->successResponse(new ProductResource($product), __('lang.Product updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->successResponse(null, __('lang.Product deleted successfully'));
    }
}
