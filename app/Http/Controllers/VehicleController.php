<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Shipment;
use App\Models\UserLog;
use App\Models\User;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicle::all();
        $shipments = Shipment::all();
        $drivers = User::where('role', 'driver')->get();
        $users = User::where('role','driver')->get();
        $companies = CompanyInfo::all();
  
        $currentModule = 'vehicle module';
        $previousModule = session('current_module');
        
        session(['current_module' => $currentModule]);

        // Log new module access
        UserLog::create([
            'name'    => Auth::user()->name,
            'actions' => 'Accessed ' . $currentModule,
            'table'   => "vehicles",
            'url'     => request()->fullUrl(),
            'user_id' => Auth::id(),
        ]);

        if ($previousModule && $previousModule !== $currentModule) {
            UserLog::create([
                'name'    => Auth::user()->name,
                'actions' => 'Exited ' . $previousModule,
                'url'     => request()->fullUrl(),
                'table'   => "vehicles",
                'user_id' => Auth::id(),
            ]);
        }

        return view('vehicles.index', compact('vehicles', 'shipments', 'drivers', 'users', 'companies'));
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

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Registered a new vehicle with registration - ' . $request->regNo . '' ,
            'url'          => $request->fullUrl(),
            'reference_id' => $vehicle->id,
            'table'        => "vehicles",
            'user_id'      => Auth::id(),
        ]);

        return back()->with('success', 'Vehicle saved successfully');
       
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

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Updated details of vehicle ' . $request->regNo . '',
            'url'          => $request->fullUrl(),
            'reference_id' => $vehicle->id,
            'table'        => "vehicles",
            'user_id'      => Auth::id(),
        ]);

        return back()->with('success', 'Vehicle updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        UserLog::create([
            'name'         => Auth::user()->name,
            'actions'      => 'Deleted vehicle with registration ' . $request->regNo . ' ',
            'url'          => $request->fullUrl(),
            'reference_id' => $vehicle->id,
            'table'        => "vehicles",
            'user_id'      => Auth::id(),
        ]);

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
