<?php

namespace App\Http\Controllers;

use App\Models\SameDayRate;
use App\Models\Office;
use Illuminate\Http\Request;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SameDayRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function nairobi_rates_sameday(){
        $offices = Office::where('id',2)->get();
       $rates = SameDayRate::where('office_id',2)->get();
 
        return view('rates.nairobi_rates_sameday')->with(['rates'=>$rates,'offices'=>$offices]); 
    }

    
    public function nrb_rates_sameday_report(){

        $rates = SameDayRate::where('office_id',2)->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('rates.nrb_rates_sameday_report' , [
            'rates'=>$rates
        ])->setPaper('a4', 'landscape');
        return $pdf->download("nrb_rates_sameday_report.pdf");
       
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedDate = $request->validate(
            [
                //'approvedBy'=>'nullable|string',
                'additional_kg'=>'nullable|string',
                //'origin'=>'nullable|string',
                'destination'=>'nullable|string',
                'rate'=>'required',
                'applicableFrom'=>'nullable|string',
                'applicableTo'=>'nullable|string',
                'status'=>'required',
                'approvalStatus'=>'required',
                'dateApproved'=>'nullable|string',
                'intercity_additional_kg'=>'nullable|string',
                'office_id' =>'required',
                'bands'=>'required'
            ]
            );
           // dd($validatedDate);
            $validatedDate['added_by'] = Auth::user()->id;

            //dd($validatedDate);
            $rate = new SameDayRate($validatedDate);
            $rate->save();
        
        return redirect()->back()->with('Success', 'Rates saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SameDayRate $sameDayRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SameDayRate $sameDayRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SameDayRate $sameDayRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SameDayRate $sameDayRate)
    {
        //
    }
}
