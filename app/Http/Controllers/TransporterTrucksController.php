<?php

namespace App\Http\Controllers;

use App\Models\TransporterTrucks;
use Illuminate\Http\Request;

class TransporterTrucksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'reg_no'=>'required',
            'driver_name'=>'required',
            'driver_contact'=>'required',
            'driver_id_no'=>'required',
            'truck_type'=>'required',
            'transporter_id' => 'required',
        ]);

        $transporter_truck = new TransporterTrucks($validatedData);
        $transporter_truck->save();
        
        return redirect()->back()->with('Success', 'Transporter Truck Saved Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(TransporterTrucks $transporterTrucks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransporterTrucks $transporterTrucks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $truck = TransporterTrucks::findOrFail($id);

        $validatedData = $request->validate([
            'reg_no' => 'required|string|max:255',
            'driver_name' => 'required|string|max:255',
            'driver_contact' => 'required|string|max:255',
            'driver_id_no' => 'nullable|numeric',
            'truck_type' => 'required|string|max:255',
            'transporter_id' => 'required|exists:transporters,id',
        ]);

        $truck->update($validatedData);

        return redirect()->back()->with('success', 'Truck updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransporterTrucks $transporterTrucks)
    {
        //
    }
}
