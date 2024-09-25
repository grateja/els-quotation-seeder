<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesRepresentative;

class SalesRepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = SalesRepresentative::where('name', 'like', "%$request->keyword%")
            ->orWhere('initials', 'like', "%$request->keyword%")
            ->orderBy('initials');
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
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:sales_representatives',
            'initials' => 'required',
            'department' => 'required',
        ];

        $request->validate($rules);

        $salesRep = SalesRepresentative::create(
            $request->only([
                'name', 'initials', 'department'
            ])
        );

        return response()->json($salesRep);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesRep = SalesRepresentative::findOrFail($id);
        return response()->json($salesRep);
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
            'initials' => 'required',
            'department' => 'required',
        ];
        $salesRep = SalesRepresentative::findOrFail($id);

        if($salesRep->name != $request->name) {
            $rules['name'] = 'required|unique:sales_representatives';
        }

        $request->validate($rules);


        $salesRep->update(
            $request->only([
                'name', 'initials', 'department'
            ])
        );

        return response()->json($salesRep);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesRep = SalesRepresentative::findOrFail($id);
        $salesRep->delete();
    }
}
