<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        $shipments = Shipment::all();
        return view('shipments.index', compact('vehicles', 'shipments'));
    }

    public function markAsDelivered(Shipment $shipment)
    {
        // Update shipment status
        $shipment->update(['status' => 'delivered']);

        // Update vehicle if it's linked to this shipment
        $vehicle = Vehicle::where('shipment_id', $shipment->id)->first();
        if ($vehicle) {
            $vehicle->update([
                'status' => 'available',
                'shipment_id' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Shipment marked as delivered and vehicle released.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shipments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
