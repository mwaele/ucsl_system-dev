<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the zones.
     */
    public function index()
    {
        $zones = Zone::all();
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

        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }

    public function checkZone(Request $request)
    {
        $exists = Zone::where('station_name', $request->station_name)->exists();
        return response()->json(['exists' => $exists]);
    }
}

