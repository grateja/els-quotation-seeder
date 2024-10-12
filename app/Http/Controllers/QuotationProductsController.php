<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\QuotationProduct;
use App\Models\Product;
use App\Models\ProductBulletDescription;
use App\Models\QuotationProductBullet;

class QuotationProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, string $quotationId)
    {
        $rules = [
            'quotation_id' => 'required|exists:quotations,id',
            'product_id' => 'required|exists:products,id',
            'unit_price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
        ];

        $message = [
            'quotation_id.exists' => 'Quotation ID is not valid',
            'product_id.exists' => 'Product ID is not valid',
        ];

        $request->merge(['quotation_id' => $quotationId]);

        $request->validate($rules, $message);

        $product = Product::findOrFail($request->product_id);
        $request->merge([
            'name' => $product->name,
            'unit_price' => $product->unit_price,
            'currency_code' => $product->currency_code,
        ]);

        return DB::transaction(function () use ($request) {
            $bullets = ProductBulletDescription::whereIn('id', $request->productBulletIds)->get();

            $quotationProduct = QuotationProduct::create($request->only([
                'quotation_id',
                'product_id',
                'name',
                'unit_price',
                'quantity',
                'discount',
                'currency_code'
            ]));

            foreach ($bullets as $bullet) {
                $quotationProductBullets = QuotationProductBullet::create([
                    'quotation_product_id' => $quotationProduct->id,
                    'description' => $bullet->label,
                    'bullet_type' => $bullet->bullet_type,
                ]);
            }

            return response()->json($quotationProduct);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
