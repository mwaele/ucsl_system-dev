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
        
        $currentModule = 'special rates module';
        $previousModule = session('current_module');

        session(['current_module' => $currentModule]);

        // Log new module access
        UserLog::create([
            'name'    => Auth::user()->name,
            'actions' => 'Accessed ' . $currentModule,
            'table'   => "special_rates",
            'url'     => request()->fullUrl(),
            'user_id' => Auth::id(),
        ]);

        if ($previousModule && $previousModule !== $currentModule) {
            UserLog::create([
                'name'    => Auth::user()->name,
                'actions' => 'Exited ' . $previousModule,
                'url'     => request()->fullUrl(),
                'table'   => "special_rates",
                'user_id' => Auth::id(),
            ]);
        }
 
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
                'origin'=>'nullable|string',
                'destination'=>'nullable|string',
                'rate'=>'required',
                'applicableFrom'=>'nullable|string',
                'applicableTo'=>'nullable|string',
                'status'=>'required',
                'type'=>'required',
                'approvalStatus'=>'required',
                'dateApproved'=>'nullable|string',
                'office_id' =>'required',
                'client_id' => 'required'
            ]
        );
        $validatedDate['added_by'] = Auth::user()->id;

        //dd($validatedDate);
        $rate = new SpecialRate($validatedDate);
        $rate->save();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Added a special rate for ' . $rate->client->name . '',
            'url'          => $request->fullUrl(),
            'reference_id' => $rate->id,
            'table'        => "special_rates",
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('special_rates.index')->with('success', 'Special Rates Saved Successfully');

    }

    public function getSpecialDestinations($office_id)
    {
        $destinations = SpecialRate::where('office_id', $office_id)
            ->where('type', 'normal')
            ->orderBy('destination', 'asc')
            ->get(['destination', 'id']);

        return response()->json($destinations);
    }

    public function getDestinations($office_id, $client_id, $service_type) 
    {
        $today = Carbon::today();

        $destinations = SpecialRate::where([
            'office_id' => $office_id,
            'client_id' => $client_id,
            'status' => 'active',
            'type' => $service_type,
        ])
        ->whereDate('applicableTo', '>=', $today)
        ->orderBy('destination', 'asc')
        ->get(['destination', 'id']);

        return response()->json($destinations);
    }

    public function getDestinationSpecialRate($office_id, $client_id, $destination_id)
    {
        $today = Carbon::today();
        $destinations = SpecialRate::where([
            'office_id' => $office_id,
            'client_id' => $client_id,
            'id' => $destination_id,
            'status' => 'active',
        ])
        ->whereDate('applicableTo', '>=', $today)
        ->orderBy('destination', 'asc')
        ->get(['destination', 'id']);

        if ($destinations) {
            return response()->json($destinations);
        }

        return response()->json(['destinations' => 'N/A'], 404);
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
            'actions'      => 'Updated special rate for ' . $rate->client->name . '',
            'url'          => $request->fullUrl(),
            'reference_id' => $rate->id,
            'table'        => "special_rates",
            'user_id'      => Auth::id(),
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
