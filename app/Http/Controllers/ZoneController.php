<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::all();
        return view('zones.index')->with('zones',$zones);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('zones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'zone_name'=>'required',
            'description' => 'nullable|string',
        ]);

        $zone = new Zone($validatedData);
        $zone->save();
        
        return redirect()->route('zones.index')->with('Success', 'Station Saved Successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $zone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $zone = Zone::find($id);
        $zone->delete();
        return redirect()->route('zones.index')->with('Success', 'Station info deleted successfully.');
    }
    public function checkZone(Request $request)
{
    $exists = Zone::where('station_name', $request->station_name)->exists();
    return response()->json(['exists' => $exists]);
}
}
