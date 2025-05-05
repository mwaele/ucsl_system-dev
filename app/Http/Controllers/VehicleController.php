<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
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
        return view('vehicles.index')->with('vehicles',$vehicles);
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
        
        return redirect()->route('vehicles.index')->with('Success', 'Vehicle saved successfully');
       
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
