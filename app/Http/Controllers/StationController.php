<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stations = Station::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Accessed stations module',
            'url'          => $request->fullUrl(),
            'table'        => "stations",
            'user_id'      => Auth::id(),
        ]);

        return view('stations.index')->with('stations',$stations);
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

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Added ' . $request->station_name . ' station',
            'url'          => $request->fullUrl(),
            'reference_id' => $station->id,
            'table'        => "stations",
            'user_id'      => Auth::id(),
        ]);
        
        return redirect()->route('stations.index')->with('Success', 'Station Saved Successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $station = Station::find($id);
        $station->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Deleted ' . $request->station_name . ' station',
            'url'          => $request->fullUrl(),
            'reference_id' => $station->id,
            'table'        => "stations",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('stations.index')->with('Success', 'Station info deleted successfully.');
    }

    public function checkStation(Request $request)
    {
        $exists = Station::where('station_name', $request->station_name)->exists();
        return response()->json(['exists' => $exists]);
    }
}
