<?php

namespace App\Http\Controllers;

use App\Models\SameDayRate;
use App\Models\Rate;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Traits\PdfReportTrait;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SameDayRateController extends Controller
{
    use PdfReportTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function nairobi_rates_sameday()
    {
        $offices = Office::where('id',2)->get();
        $rates = Rate::where(['office_id'=>2,'type'=>'Same Day'])->get();
 
        return view('rates.nairobi_rates_sameday')->with(['rates'=>$rates,'offices'=>$offices]); 
    }

    
    public function nrb_rates_sameday_report()
    {
        $rates = SameDayRate::where('office_id', 2)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'rates.nrb_rates_sameday_report',
            ['rates' => $rates],
            'nrb_rates_sameday_report.pdf',
            'a4',
            'landscape'
        );
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
                'bands'=>'required',
                
            ]
            );
           // dd($validatedDate);
            $validatedDate['added_by'] = Auth::user()->id;
            $validatedDate['zone_id'] = 1;
            $validatedDate['type'] = 'Same Day';

            //dd($validatedDate);
            $rate = new Rate($validatedDate);
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
