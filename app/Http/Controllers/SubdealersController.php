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
        $rules = [
            'abbr' => 'required',
            'name' => 'required',
            'area' => 'required',
        ];

        $request->validate($rules);

        $subdealer = Subdealer::findOrFail($id);

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
    public function destroy(string $id)
    {
        //
    }
}
