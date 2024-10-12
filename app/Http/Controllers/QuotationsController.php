<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Quotation;

class QuotationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = Quotation::leftJoin('sales_representatives', 'sales_representatives.id','=','quotations.sales_representative_id')
            ->leftJoin('customers', 'customers.id', '=', 'quotations.customer_id')
            ->leftJoin('subdealers', 'subdealers.id', '=', 'quotations.subdealer_id')
            ->select(
                'quotations.*',
                'sales_representatives.name as sales_rep',
                'customers.name as customer_name',
                'subdealers.name as subdealer_name',
                DB::raw('coalesce(sales_representatives.name, subdealers.name) as fao')
            )
            ->where(function($query) use ($request) {
                $query->where('quotation_number', 'like', "%$request->keyword%");
            })->orderByDesc('created_at')->paginate(30);

        return response()->json($result);
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
            'subdealer_id' => 'nullable|exists:subdealers,id',
            'sales_representative_id' => 'nullable|exists:sales_representatives,id',
            'customer_id' => 'required|exists:customers,id',
        ];

        $messages = [
            'subdealer_id.exists' => 'Subdealer ID is not valid.',
            'sales_representative_id.exists' => 'Sales Representative ID is not valid.',
            'customer_id.exists' => 'Customer ID is not valid.',
        ];

        // Add custom validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // Custom validation logic for sales_representative_id and subdealer_id
        $validator->after(function ($validator) use ($request) {
            $hasSalesRep = !is_null($request->sales_representative_id);
            $hasSubdealer = !is_null($request->subdealer_id);

            \Log::debug($hasSalesRep);
            \Log::debug($hasSalesRep);

            if ($hasSalesRep && $hasSubdealer) {
                $validator->errors()->add('sales_representative_id', 'You cannot provide both Sales Representative ID and Subdealer ID.');
                $validator->errors()->add('subdealer_id', 'You cannot provide both Sales Representative ID and Subdealer ID.');
            } elseif (!$hasSalesRep && !$hasSubdealer) {
                $validator->errors()->add('sales_representative_id', 'You must provide either Sales Representative ID or Subdealer ID.');
                $validator->errors()->add('subdealer_id', 'You must provide either Sales Representative ID or Subdealer ID.');
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $quotationNumber = Quotation::generateQuotationNumber('test');
        $userId = auth('sanctum')->user()->id;

        $request->merge([
            'quotation_number' => $quotationNumber,
            'user_id' => $userId,
        ]);

//        $request->validate($rules, $messages);

        $quotation = Quotation::create($request->only([
            'subdealer_id',
            'sales_representative_id',
            'customer_id',
            'quotation_number',
            'user_id',
        ]));

        return response()->json($quotation);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $quotationId)
    {
        $quotation = Quotation::with(
            'customer',
            'subdealer',
            'salesRepresentative',
            'quotationProducts.quotationProductBullets',
            'quotationProducts'
        )->findOrFail($quotationId);

        return response()->json($quotation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $quotationId)
    {
        $rules = [
            'subdealer_id' => 'nullable|exists:subdealers,id',
            'sales_representative_id' => 'nullable|exists:sales_representatives,id',
            'customer_id' => 'required|exists:customers,id',
        ];

        $messages = [
            'subdealer_id.exists' => 'Subdealer ID is not valid.',
            'sales_representative_id.exists' => 'Sales Representative ID is not valid.',
            'customer_id.exists' => 'Customer ID is not valid.',
        ];

        // Add custom validation
        $validator = Validator::make($request->all(), $rules, $messages);

        // Custom validation logic for sales_representative_id and subdealer_id
        $validator->after(function ($validator) use ($request) {
            $hasSalesRep = !is_null($request->sales_representative_id);
            $hasSubdealer = !is_null($request->subdealer_id);

            \Log::debug($hasSalesRep);
            \Log::debug($hasSalesRep);

            if ($hasSalesRep && $hasSubdealer) {
                $validator->errors()->add('sales_representative_id', 'You cannot provide both Sales Representative ID and Subdealer ID.');
                $validator->errors()->add('subdealer_id', 'You cannot provide both Sales Representative ID and Subdealer ID.');
            } elseif (!$hasSalesRep && !$hasSubdealer) {
                $validator->errors()->add('sales_representative_id', 'You must provide either Sales Representative ID or Subdealer ID.');
                $validator->errors()->add('subdealer_id', 'You must provide either Sales Representative ID or Subdealer ID.');
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $quotation = Quotation::findOrFail($quotationId);
        $userId = auth('sanctum')->user()->id;

        $request->merge([
            'user_id' => $userId,
        ]);

        $quotation->update($request->only([
            'subdealer_id',
            'sales_representative_id',
            'customer_id',
            'user_id',
        ]));

        return response()->json($quotation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
