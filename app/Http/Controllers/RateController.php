<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\Office;
use App\Models\Zone;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PdfReportTrait;
use Illuminate\Http\Request;
use Auth;

class RateController extends Controller
{
    use PdfReportTrait;

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

    public function mombasa_office()
    {
        $rates = Rate::where('office_id',1)->get();
        $zones = Zone::all();

        $offices = Office::where('id',1)->get();
 
        return view('rates.mombasa_rates')->with(['rates'=>$rates,'zones'=>$zones,'offices'=>$offices]);

    }

    public function nairobi_office()
    {
        $rates = Rate::where('office_id',2)->get();
        $zones = Zone::all();

        $offices = Office::where('id',2)->get();
 
        return view('rates.nairobi_rates')->with(['rates'=>$rates,'zones'=>$zones,'offices'=>$offices]);

    }

    public function getDestinations($office_id)
    {
        $destinations = Rate::where('office_id', $office_id)
                    ->where('type', 'normal')
                    ->orderBy('destination', 'asc')
                    ->get(['destination', 'id']);

        return response()->json($destinations);
    }
    
    public function getDestinationSameDay($office_id)
    {
        $destinations = Rate::where('office_id', $office_id)
            ->whereIn('type', ['Same Day', 'same_day'])
            ->orderBy('destination', 'asc')
            ->get(['destination', 'id']);

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

    public function getCostSameDay($originId, $destinationId)
    {
        $rate = Rate::where('office_id', $originId)
                    ->where('destination', $destinationId)
                    ->whereIn('type', ['Same Day', 'same_day'])
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


    public function rates_report()
    {
        $rates = Rate::orderBy('created_at', 'desc')->get();

        return $this->renderPdfWithPageNumbers(
            'rates.rate_report',
            ['rates' => $rates],
            'rates_report.pdf',
            'a4',
            'landscape'
        );
    }
    
    public function msa_rates_report()
    {
        $rates = Rate::orderBy('created_at', 'desc')
            ->where('office_id', 1)
            ->get();

        $head = "Mombasa Overnight Rates Report";
        $title = "Overnight Rates From Mombasa to Other Destinations";

        return $this->renderPdfWithPageNumbers(
            'rates.rate_report',
            [
                'rates' => $rates,
                'title' => $title,
                'head' => $head,
            ],
            'mombasa_rates_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function nrb_rates_report()
    {
        $rates = Rate::orderBy('created_at', 'desc')
            ->where('office_id', 2)
            ->get();

        $head = "Nairobi Overnight Rates Report";
        $title = "Overnight Rates From Nairobi to other Destinations";

        return $this->renderPdfWithPageNumbers(
            'rates.rate_report',
            [
                'rates' => $rates,
                'title' => $title,
                'head' => $head,
            ],
            'nairobi_rates_report.pdf',
            'a4',
            'landscape'
        );
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
        // Validate incoming data
        $validatedData = $request->validate([
            'office_id'        => 'required|exists:offices,id',
            'zone_id'          => 'required|exists:zones,id',
            'destination'      => 'nullable|string|max:255',
            'rate'             => 'required|numeric|min:0',
            'applicableFrom'   => 'nullable|date',
            'applicableTo'     => 'nullable|date|after_or_equal:applicableFrom',
            'approvalStatus'   => 'nullable|in:pending,approved',
            'status'           => 'nullable|in:active,closed',
            'type'             => 'nullable|in:normal,pharmaceutical',
        ]);

        // Update the Rate model with validated data
        $rate->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Rate updated successfully!');
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
