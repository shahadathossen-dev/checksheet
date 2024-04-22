<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::all()->sortByDesc('id');
        return response()->json($products, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Create new product
            $product = Product::create($request->only('product_name', 'price', 'price_with_vat'));

            // Collect product attributes from request and sync
            $attributes = collect($request->input('attributes'))->values();
            $product->attributes()->createMany($attributes->toArray());

            // Sync product image
            if ($request->hasFile('image')) {
                $product->addMedia($request->image)->toMediaCollection('primary');
            }
        });

        return response()->json(['message' => 'Data stored successfully.'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        // Start from here ...
        return response()->json($product, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        // Start from here ...
        DB::transaction(function () use ($request, $product) {
            // Create new product
            $product->update($request->only('product_name', 'price', 'price_with_vat'));

            // Collect product attributes from request
            $attributes = collect($request->input('attributes'))->values();

            // Clean removed attributes except new added items
            $product->attributes()->whereNotIn('id', $attributes->pluck('id')->reject(fn ($id) => empty($id)))->delete();

            // Update or create attributes
            $attributes->each(function ($attribute) use ($product) {
                $product->attributes()->updateOrCreate(['name' => $attribute['name']], ['value' => $attribute['value']]);
            });

            // Sync product image
            if ($request->hasFile('image')) {
                $product->addMedia($request->image)->toMediaCollection('primary');
            }
        });

        return response()->json(['message' => 'Data updated successfully.'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        if (!$product)
            return response()->json(['message' => 'Data not found.'], Response::HTTP_NOT_FOUND);

        // Delete product
        $product->delete();
        return response()->json(['message' => 'Data deleted successfully.'], Response::HTTP_OK);
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        // Restore product from trash
        Product::findOrFail($id)->onlyTrashed()->restore();
        return response()->json(['message' => 'Data restored successfully.'], Response::HTTP_OK);
    }

    /**
     * Force delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        // Delete product permanently
        Product::findOrFail($id)->withTrashed()->forceDelete();
        return response()->json(['message' => 'Data deleted permanently.'], Response::HTTP_OK);
    }
}
