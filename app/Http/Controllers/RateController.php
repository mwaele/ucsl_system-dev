<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Office;
use App\Models\Zone;
use Illuminate\Http\Request;
use Auth;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = Rate::all();
        $offices = Office::all();
        $zones = Zone::all();
 
        return view('rates.index')->with(['rates'=>$rates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $offices = Office::all();
        $zones = Zone::all();
        return view('rates.create')->with(['offices'=>$offices,'zones'=>$zones]);
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
                'zone_id'=>'required'
            ]
            );
            $validatedDate['added_by'] = Auth::user()->id;

            //dd($validatedDate);
            $rate = new Rate($validatedDate);
            $rate->save();
        
        return redirect()->route('rates.index')->with('Success', 'Vehicle saved successfully');


    }

    /**
     * Display the specified resource.
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $rate = Rate::find($id);
        $rate->delete();
        return redirect()->route('rates.index')->with('Success', 'Rate deleted successfully.');
    }
}
