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
    public function show(SalesRepresentative $salesRep)
    {
        return response()->json($salesRep);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesRepresentative $salesRep)
    {
        $rules = [
            'name' => 'required',
            'initials' => 'required',
            'department' => 'required',
        ];

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
    public function destroy(SalesRepresentative $salesRep)
    {
        $salesRep->delete();
    }
}
