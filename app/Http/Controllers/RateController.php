<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Office;
use App\Models\Zone;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function mombasa_office(){
        $rates = Rate::where('office_id',1)->get();
        $zones = Zone::all();

        $offices = Office::where('id',1)->get();
 
        return view('rates.mombasa_rates')->with(['rates'=>$rates,'zones'=>$zones,'offices'=>$offices]);

    }
    public function nairobi_office(){
        $rates = Rate::where('office_id',2)->get();
        $zones = Zone::all();

        $offices = Office::where('id',2)->get();
 
        return view('rates.nairobi_rates')->with(['rates'=>$rates,'zones'=>$zones,'offices'=>$offices]);

    }

    public function getDestinations($office_id)
    {
        $destinations = Rate::where('office_id', $office_id)->get(['destination','id']);

        return response()->json($destinations);
    }
    
    public function getCost($originId, $destinationId)
    {
        $rate = Rate::where('office_id', $originId)
                    ->where('destination', $destinationId)
                    ->first();

        if ($rate) {
            return response()->json(['cost' => $rate->rate]);
        }

        return response()->json(['cost' => 'N/A'], 404);
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
                'type'=>'required',
                'dateApproved'=>'nullable|string',
                'office_id' =>'required',
                'zone_id'=>'required'
            ]
            );
            $validatedDate['added_by'] = Auth::user()->id;

            //dd($validatedDate);
            $rate = new Rate($validatedDate);
            $rate->save();
        
        return redirect()->back()->with('Success', 'Rates saved successfully');
    }


    public function rates_report(){

        $rates = Rate::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('rates.rate_report' , [
            'rates'=>$rates
        ])->setPaper('a4', 'landscape');
        return $pdf->download("rates_report.pdf");
       
    }
    
    public function msa_rates_report(){

        $rates = Rate::orderBy('created_at', 'desc')->where('office_id',1)->get();
        $head = "Mombasa Overnight Rates Report";
        $title = "Overnight Rates From Mombasa to Other Destinations";
        $pdf = Pdf::loadView('rates.rate_report' , [
            'rates'=>$rates,
            'title' => $title,
            'head'=>$head,
        ])->setPaper('a4', 'landscape');
        return $pdf->download("mombasa_rates_report.pdf");
       
    }

    public function nrb_rates_report(){

        $rates = Rate::orderBy('created_at', 'desc')->where('office_id',2)->get();
        $head = "Nairobi Overnight Rates Report";
        $title = "Overnight Rates From Nairobi to other Destinations";
        $pdf = Pdf::loadView('rates.rate_report' , [
            'rates'=>$rates,
            'title' => $title,
            'head'=>$head,
        ])->setPaper('a4', 'landscape');
        return $pdf->download("nairobi_rates_report.pdf");
       
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
