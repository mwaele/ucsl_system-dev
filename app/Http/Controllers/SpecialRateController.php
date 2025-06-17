<?php

namespace App\Http\Controllers;

use App\Models\SpecialRate;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Office;
use App\Models\Zone;
use Auth;

class SpecialRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = SpecialRate::all();
        $offices = Office::all();
        $zones = Zone::all();
 
        return view('special_rates.index')->with(['rates'=>$rates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $offices = Office::all();
        $zones = Zone::all();
        return view('special_rates.create')->with(['offices'=>$offices,'zones'=>$zones, 'clients'=>$clients]);
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
        
        return redirect()->route('special_rates.index')->with('Success', 'Special Rates Saved Successfully');

    }

     public function getDestinations($office_id, $client_id)
    {
        $destinations = SpecialRate::where(['office_id' => $office_id,'client_id' => $client_id])->get(['destination','id']);

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
    public function update(Request $request, SpecialRate $specialRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialRate $specialRate)
    {
        //
    }
}
