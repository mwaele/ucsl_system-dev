<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\TransporterTrucks;
use Illuminate\Http\Request;

class TransporterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        

        $transporters = Transporter::all();
        return view('transporters.index')->with(['transporters'=>$transporters]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         do {
            $number = 'TRN-' . mt_rand(10000, 99999);
        } while (Transporter::where('account_No', $number)->exists());
        return view('transporters.create')->with('account_no',$number);
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
        ]);
            if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $validatedData['signature'] = $path;
        }
        

        $transporter = new Transporter($validatedData);
        $transporter->save();
        
        return redirect()->route('transporters.index')->with('Success', 'Transporter Saved Successfully');
    }

    public function fetchTrucks($id){
       $name = Transporter::find($id)?->name;
        
        $transporter_trucks = TransporterTrucks::where(['transporter_id'=>$id,'status'=>'available'])->get();
        return view('transporter_trucks.trucks')->with(['trucks'=>$transporter_trucks,'id'=>$id,'name'=>$name]);
    
    }

    public function getTrucks($transporterId)
    {
        $trucks = TransporterTrucks::where(['transporter_id'=> $transporterId,'status'=>'available'])->get(['id', 'reg_no']);
        return response()->json($trucks);
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
    public function edit(Transporter $transporter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transporter $transporter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transporter $transporter)
    {
        //
    }
}
