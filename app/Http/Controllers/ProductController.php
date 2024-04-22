<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Product::class)) {
            abort(403);
        }

        // Start from here ...
        return Inertia::render('Products/Index', [
            'products' => Product::filter($request->all())
                ->sorted()
                ->paginate()
                ->withQueryString(),
            'query'  => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Product::class)) {
            abort(403);
        }

        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        if ($request->user()->cannot('create', Product::class)) {
            abort(403);
        }

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

        session()->flash('flash.banner', 'Created successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->saveAndContinue) {
            return back();
        }
        return redirect()->route('products.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, Product $product)
    {
        if ($request->user()->cannot('update', $product)) {
            abort(403);
        }

        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        if ($request->user()->cannot('update', $product)) {
            abort(403);
        }

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

        session()->flash('flash.banner', 'Updated successfully.');
        session()->flash('flash.bannerStyle', 'success');

        if ($request->updateAndContinue) {
            return back();
        }
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Product $product)
    {
        if ($request->user()->cannot('delete', $product)) {
            abort(403);
        }

        // Delete product
        $product->delete();

        session()->flash('flash.banner', 'Deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->user()->cannot('viewAny', Product::class)) {
            abort(403);
        }

        // Restore product from trash
        Product::findOrFail($id)->onlyTrashed()->restore();

        session()->flash('flash.banner', 'Deleted permanently.');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    /**
     * Force delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->user()->cannot('delete', Product::class)) {
            abort(403);
        }

        // Delete product permanently
        Product::findOrFail($id)->withTrashed()->forceDelete();

        session()->flash('flash.banner', 'Deleted permanently.');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }
}
