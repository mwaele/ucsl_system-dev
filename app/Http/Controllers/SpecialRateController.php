<?php

namespace App\Http\Controllers;

use App\Models\SpecialRate;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Office;
use App\Models\Zone;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SpecialRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rates = SpecialRate::all();
        $offices = Office::all();
        $zones = Zone::all();
        $clients = Client::all();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' viewed special rates list at ' . now(),
            'url'          => $request->fullUrl(),
            'table'        => "special_rates",
            'user_id'      => Auth::id(),
        ]);
 
        return view('special_rates.index', compact('rates','offices','zones','clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedDate = $request->validate(
            [
                'approvedBy'=>'nullable|string',
                'zone'=>'nullable|string',
                'origin'=>'nullable|string',
                'destination'=>'nullable|string',
                'rate'=>'required',
                'applicableFrom'=>'nullable|string',
                'applicableTo'=>'nullable|string',
                'status'=>'required',
                'approvalStatus'=>'required',
                'dateApproved'=>'nullable|string',
                'office_id' =>'required',
                'zone_id'=>'required',
                'client_id' => 'required'
            ]
        );
        $validatedDate['added_by'] = Auth::user()->id;

        //dd($validatedDate);
        $rate = new SpecialRate($validatedDate);
        $rate->save();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' added a special rate for ' . $rate->client->name . ' at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('special_rates.index')->with('success', 'Special Rates Saved Successfully');

    }

    public function getDestinations($office_id, $client_id)
    {
        $today = Carbon::today(); // or use now() if time matters
        $destinations = SpecialRate::where([
            'office_id' => $office_id,
            'client_id' => $client_id,
            'status' => 'active'
        ])
        ->whereDate('applicableTo', '>=', $today)->get(['destination', 'id']);

        return response()->json($destinations);
    }
    
    public function getCost($originId, $destinationId, $client_id)
    {
        $rate = SpecialRate::where(['office_id'=>$originId,'destination'=> $destinationId,'client_id'=> $client_id])->first();

        if ($rate) {
            return response()->json(['cost' => $rate->rate]);
        }

        return response()->json(['cost' => 'N/A'], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpecialRate $specialRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialRate $specialRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'office_id' => 'required|integer',
            'zone_id' => 'required|integer',
            'destination' => 'nullable|string|max:255',
            'rate' => 'required|numeric',
            'applicableFrom' => 'nullable|date',
            'applicableTo' => 'nullable|date',
            'approvalStatus' => 'nullable|string',
            'client_id' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);

        $rate = SpecialRate::findOrFail($id);
        $rate->update($validated);

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => Auth::user()->name . ' updated special rate for ' . $rate->client->name . ' at ' . now(),
            'url'          => $request->fullUrl(),
            'reference_id' => Auth::id(),
            'table'        => Auth::user()->getTable(),
        ]);

        return redirect()->route('special_rates.index')->with('success', 'Special Rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialRate $specialRate)
    {
        //
    }
}
