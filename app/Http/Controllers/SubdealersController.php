<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subdealer;

class SubdealersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = Subdealer::where(function($query) use ($request) {
            $query->where('name', 'like', "%$request->keyword%")
                ->orWhere('abbr', 'like', "%$request->keyword%");
        })->orderBy('abbr')->paginate(30);

        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'abbr' => 'required',
            'name' => 'required',
            'area' => 'required',
        ];

        $request->validate($rules);

        $subdealer = Subdealer::create($request->only([
            'abbr',
            'name',
            'area'
        ]));

        return response()->json($subdealer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subdealer $subdealer)
    {
        return response()->json($subdealer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subdealer $subdealer)
    {
        $rules = [
            'abbr' => 'required',
            'name' => 'required',
            'area' => 'required',
        ];

        $request->validate($rules);

        $subdealer->update($request->only([
            'abbr',
            'name',
            'area'
        ]));

        return response()->json($subdealer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subdealer $subdealer)
    {
        $subdealer->delete();
    }
}
