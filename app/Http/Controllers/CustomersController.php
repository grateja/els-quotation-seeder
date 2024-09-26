<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomersController extends Controller
{
    public function generateCRN() {
        $year = date('Y');
        $latestCustomer = Customer::latest()->first();

        if (!$latestCustomer) {
            $lastNumber = 1000;
        } else {
            $lastNumber = substr($latestCustomer->crn, -4) + 1;
        }

        $customerNumber = 'PH' . $year . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return $customerNumber;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $result = Customer::join('sales_representatives', 'customers.sales_representative_id', '=', 'sales_representatives.id')
        //     ->join('subdealers', 'customers.subdealer_id', '=', 'subdealers.id')
        //     ->select('customers.*', 'sales_representatives.name as sales_representative_name', 'subdealers.name as subdealer_name')
        //     ->where(function($query) use ($request) {
        //         $query->where('customers.name', 'like', "%$request->keyword%");
        //     })->orderBy('crn');

        $result = Customer::where(function($query) use ($request) {
            $query->where('customers.name', 'like', "%$request->keyword%");
        })->orderBy('crn');

        return response()->json($result->paginate(30));
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
    public function store(Request $request) {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'sales_representative_id' => 'nullable|exists:sales_representatives,id', // Ensure the sales representative exists
            'subdealer_id' => 'nullable|exists:subdealers,id', // Ensure the sales representative exists
        ];

        $messages = [
            'sales_representative_id.exists' => 'The selected sales representative is invalid.',
            'subdealer_id.exists' => 'The selected subdealer is invalid.',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules, $messages);

        // Generate a CRN
        $request->merge(['crn' => $this->generateCRN()]);


        $customer = Customer::create($request->only([
            'crn',
            'name',
            'address',
            'contact_number',
            'sales_representative_id',
            'subdealer_id',
        ]));

        // Load the sales representative relationship
        // $customer->load('salesRepresentative');

        // Add sales representative name to the response
        // $response = $customer->toArray(); // Convert the customer object to an array
        // $response['sales_representative_name'] = $customer->salesRepresentative->name; // Add the sales representative name

        // Return the response
        return response()->json($customer);
    }

    // public function store(Request $request)
    // {
    //     $rules = [
    //         'name' => 'required',
    //         'address' => 'required',
    //         'contact_number' => 'required',
    //         'sales_representative_id' => 'required'
    //     ];

    //     if($request->validate($rules)) {
    //         $crn = $this->generateCRN();
    //         $customer = Customer::create([
    //             'crn' => $crn,
    //             'name' => $request->name,
    //             'address' => $request->address,
    //             'contact_number' => $request->contact_number,
    //             'sales_representative_id' => $request->sales_representative_id,
    //         ]);

    //         $customer['sales_representative_name'] = $customer->salesRepresentative['name'];

    //         return response()->json($customer);
    //     }
    // }

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
    public function update(Request $request, string $customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $rules = [
            'name' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'sales_representative_id' => 'nullable|exists:sales_representatives,id', // Ensure the sales representative exists
            'subdealer_id' => 'nullable|exists:subdealers,id', // Ensure the sales representative exists
        ];

        $messages = [
            'sales_representative_id.exists' => 'The selected sales representative is invalid.',
            'subdealer_id.exists' => 'The selected subdealer is invalid.',
        ];

        if($customer->name != $request->name) {
            $rules['name'] = 'required|unique:customers,name';
        }

        // Validate the request data
        $validatedData = $request->validate($rules, $messages);

        $customer->update($request->only([
            'name',
            'address',
            'contact_number',
            'sales_representative_id',
            'subdealer_id',
        ]));

        // Load the sales representative relationship
        // $customer->load('salesRepresentative');

        // Add sales representative name to the response
        // $response = $customer->toArray(); // Convert the customer object to an array
        // $response['sales_representative_name'] = $customer->salesRepresentative->name; // Add the sales representative name

        // Return the response
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
    }
}
