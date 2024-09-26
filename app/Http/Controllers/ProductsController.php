<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $productLineId)
    {
        $result = Product::with('bullets')->where('product_line_id', $productLineId)->where(function($query) use ($request) {
            $query->where('name', 'like', "%$request->keyword%");
        })->orderByDesc('created_at')
        ->selectRaw('products.*, concat(brand, "-" , model) as alias');

        return response()->json(
            $result->paginate(30)
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        $product = Product::with('bullets')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
            'product_line_id' => 'required',
            'unit_price' => 'required',
        ];

        $request->validate($rules);

        $product = Product::findOrFail($id);

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
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
