<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\TransporterTrucks;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PdfReportTrait;
use Carbon\Carbon;

class TransporterController extends Controller
{
    use PdfReportTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transporters = Transporter::orderBy('created_at', 'desc')->get();
        $account_no = rand(100000, 999999);
        return view('transporters.index', compact('transporters', 'account_no'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        do {
            $account_no = 'TRN-' . mt_rand(10000, 99999);
        } while (Transporter::where('account_No', $account_no)->exists());
        return view('transporters.create')->with('account_no',$account_no);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'=>'required',
            'phone_no'=>'required',
            'email'=>'required|email|max:255',
            'reg_details'=>'required|string|max:255',
            'account_no'=>'required',
            'cbv_no' => 'nullable|string',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'transporter_type' => 'required'
        ]);
            if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $validatedData['signature'] = $path;
        }
        

        $transporter = new Transporter($validatedData);
        $transporter->save();
        
        return redirect()->route('transporters.index')->with('success', 'Transporter Saved Successfully');
    }

    public function fetchTrucks($id)
    {
       $name = Transporter::find($id)?->name;
        
        $transporter_trucks = TransporterTrucks::where(['transporter_id'=>$id])->get();
        return view('transporter_trucks.trucks')->with(['trucks'=>$transporter_trucks,'id'=>$id,'name'=>$name, 'id'=>$id]);
    
    }

    public function getTrucks($transporterId)
    {
        $trucks = TransporterTrucks::where(['transporter_id'=> $transporterId,'status'=>'available'])->get(['id', 'reg_no']);
        return response()->json($trucks);
    }

    public function transporter_report()
    {
        $transporters = Transporter::orderBy('created_at', 'desc')->get();

        return $this->renderPdfWithPageNumbers(
            'transporters.truck_list_report',
            ['transporters' => $transporters],
            'transporters_report.pdf',
            'a4',
            'landscape'
        );
    }

    public function transporterTrucksReport($id)
    {
       $name = Transporter::find($id)?->name;
        
        $transporter_trucks = TransporterTrucks::where(['transporter_id'=>$id])->get();
        //dd($transporter_trucks);
        $pdf = Pdf::loadView('transporter_trucks.trucks_report' , [
            'trucks'=>$transporter_trucks,'name'=>$name
        ])->setPaper('a4', 'landscape');
        return $pdf->download("transporter_trucks_report.pdf");
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Transporter $transporter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transporter = Transporter::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email|max:255',
            'reg_details' => 'required|string|max:255',
            'account_no' => 'required',
            'cbv_no' => 'nullable|string',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'transporter_type' => 'required'
        ]);

        // Handle signature upload if a new one is provided
        if ($request->hasFile('signature')) {

            // Delete old signature if exists
            if ($transporter->signature && \Storage::disk('public')->exists($transporter->signature)) {
                \Storage::disk('public')->delete($transporter->signature);
            }

            // Store new file
            $path = $request->file('signature')->store('signatures', 'public');
            $validatedData['signature'] = $path;
        }

        // Update transporter data
        $transporter->update($validatedData);

        return redirect()->route('transporters.index')->with('success', 'Transporter Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transporter $transporter)
    {
        $transporter->delete();

        return redirect()->route('transporters.index')->with('success', 'Transporter deleted successfully');
    }
}
