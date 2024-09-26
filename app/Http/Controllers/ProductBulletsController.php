<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Enums\BulletTypeEnum;
use App\Enums\UnitEnum;
use App\Models\ProductBulletDescription;

class ProductBulletsController extends Controller
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
    public function store(Request $request, string $productId)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'description' => 'required',
            'quantity' => 'nullable|numeric',
            'unit' => ['nullable', Rule::in(array_column(UnitEnum::cases(), 'value'))],
            'bullet_type' => ['required', Rule::in(array_column(BulletTypeEnum::cases(), 'value'))],
            'stack_order' => 'required|numeric',
        ];

        $messages = [
            'product_id.required' => 'Product ID is required.',
            'product_id.exists' => 'The selected product is invalid.',
        ];

        $request->merge(['product_id' => $productId]);

        $request->validate($rules, $messages);

        $bullet = ProductBulletDescription::create(
            $request->only([
                'description',
                'bullet_type',
                'product_id',
                'stack_order',
                'quantity',
                'unit',
            ])
        );

        return response()->json($bullet);
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
    public function update(Request $request, string $bulletId)
    {
        $rules = [
            'description' => 'required',
            'quantity' => 'nullable|numeric',
            'unit' => ['nullable', Rule::in(array_column(UnitEnum::cases(), 'value'))],
            'bullet_type' => ['required', Rule::in(array_column(BulletTypeEnum::cases(), 'value'))],
            'stack_order' => 'required|numeric',
        ];

        // Validate the request data
        $request->validate($rules);

        $bullet = ProductBulletDescription::findOrFail($bulletId);

        // Update the bullet description with the validated request data
        $bullet->update($request->only([
            'description',
            'bullet_type',
            'product_id',
            'stack_order',
            'quantity',
            'unit',
        ]));

        return response()->json($bullet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $bulletId)
    {
        $bullet = ProductBulletDescription::findOrFail($bulletId);
        $bullet->delete();
    }

    public function moveUp(string $bulletId) {
        // Find the current model
        $bullet = ProductBulletDescription::findOrFail($bulletId);

        // Find the model above
        $modelDown = ProductBulletDescription::where('stack_order', '<', $bullet->stack_order)
                                ->orderBy('stack_order', 'desc')
                                ->first();

        if ($modelDown) {
            // Swap stack orders
            $currentStackOrder = $bullet->stack_order;
            $bullet->stack_order = $modelDown->stack_order;
            $modelDown->stack_order = $currentStackOrder;

            // Save both models
            $bullet->save();
            $modelDown->save();
        }

        return response()->json([
            'moveUp' => $bullet,
            'moveDown' => $modelDown,
        ]);
    }

    public function moveDown(string $bulletId) {
        // Find the current model
        $bullet = ProductBulletDescription::findOrFail($bulletId);

        // Find the model above
        $moveUp = ProductBulletDescription::where('stack_order', '>', $bullet->stack_order)
                                ->orderBy('stack_order', 'asc')
                                ->first();

        if ($moveUp) {
            // Swap stack orders
            $currentStackOrder = $bullet->stack_order;
            $bullet->stack_order = $moveUp->stack_order;
            $moveUp->stack_order = $currentStackOrder;

            // Save both models
            $bullet->save();
            $moveUp->save();
        }

        return response()->json([
            'moveUp' => $moveUp,
            'moveDown' => $bullet,
        ]);
    }
}
