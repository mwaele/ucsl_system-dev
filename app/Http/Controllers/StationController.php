<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::all();
        return view('stations.index')->with('stations',$stations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'station_name'=>'required',
            'station_prefix' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $station = new Station($validatedData);
        $station->save();
        
        return redirect()->route('stations.index')->with('Success', 'Station Saved Successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Station $station)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $station)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $station = Station::find($id);
        $station->delete();
        return redirect()->route('stations.index')->with('Success', 'Station info deleted successfully.');
    }
    public function checkStation(Request $request)
{
    $exists = Station::where('station_name', $request->station_name)->exists();
    return response()->json(['exists' => $exists]);
}
}
