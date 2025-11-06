<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Shipment;
use App\Models\User;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        $shipments = Shipment::all();
        $drivers = User::where('role', 'driver')->get();
        $users = User::where('role','driver')->get();
        $companies = CompanyInfo::all();
        return view('vehicles.index', compact('vehicles', 'shipments', 'drivers', 'users', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = CompanyInfo::all();
        $users = User::where('role','driver')->get();

        return view('vehicles.create')->with([
            'users'=>$users,
            'companies'=>$companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'regNo'=>'required',
            'type'=>'required',
            'color' => 'nullable|string',
            'tonnage'=>'required',
            'status'=>'required',
            'description'=>'required',
            'user_id'=>'required',
            'ownedBy'=>'required',
        ]);

        $vehicle = new Vehicle($validatedData);
        $vehicle->save();
        
        return redirect()->route('vehicles.index')->with('success', 'Vehicle saved successfully');
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validatedData = $request->validate([
            'regNo'      => 'required',
            'type'       => 'required',
            'color'      => 'nullable|string',
            'tonnage'    => 'required',
            'status'     => 'required',
            'description'=> 'required',
            'user_id'    => 'required',
            'ownedBy'    => 'required',
        ]);

        $vehicle->update($validatedData);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully');
    }

    /**
     * Allocate the specified resource from storage to a shipment.
     */
    public function allocate(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
        ]);

        $vehicle->update([
            'shipment_id' => $request->shipment_id,
            'status' => 'allocated',
        ]);

        return redirect()->back()->with('success', 'Vehicle allocated successfully.');
    }

}
