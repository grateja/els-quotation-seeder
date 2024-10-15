<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $productLineId = null)
    {
        $result = Product::with('bullets')
            ->when($productLineId, function ($query) use ($productLineId) {
                // If $productLineId is not null, add the condition
                $query->where('product_line_id', $productLineId);
            })
            ->where(function($query) use ($request) {
                $query->where('name', 'like', "%$request->keyword%");
            })->orderBy('name')
            ->selectRaw('products.*, concat(brand, "-" , model) as alias');

        return response()->json(
            $result->paginate(0)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'product_line_id' => 'required',
            'unit_price' => 'required',
        ];

        $request->validate($rules);

        $product = Product::create($request->only([
            'product_line_id',
            'name',
            'model',
            'brand',
            'unit_price',
            'currency_code',
        ]));

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product = Product::with('bullets')->findOrFail($id);
        $product->load('bullets');
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required',
            'product_line_id' => 'required',
            'unit_price' => 'required',
        ];

        $request->validate($rules);

        $product->update($request->only([
            'product_line_id',
            'name',
            'model',
            'brand',
            'unit_price',
            'currency_code'
        ]));

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
