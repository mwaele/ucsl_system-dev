<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\UserLog;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the zones.
     */
    public function index(Request $request)
    {
        $zones = Zone::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed a zone at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return view('zones.index', compact('zones'));
    }

    /**
     * Store a newly created zone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        Zone::create($validated);

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' created a zone at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
    }

    /**
     * Update the specified zone in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'zone_name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $zone = Zone::findOrFail($id);
        $zone->update($validated);

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' updated a zone at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy(Request $request, $id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' deleted a zone at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }

    public function checkZone(Request $request)
    {
        $exists = Zone::where('station_name', $request->station_name)->exists();
        return response()->json(['exists' => $exists]);
    }
}

