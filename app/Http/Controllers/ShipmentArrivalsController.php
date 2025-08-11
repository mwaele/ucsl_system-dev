<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentArrival;
use App\Models\ShipmentArrivalItem;
use Auth;

class ShipmentArrivalsController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

 public function saveArrivals(Request $request, $id)
    {
        $validatedData = $request->validate([
            'requestId' => 'required|string',
            'dateRequested' => 'nullable|date',
            'cost' => 'nullable|numeric',
            'base_cost' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'vehicleDisplay' => 'nullable|string',
            'userId' => 'nullable|string',
            'billing_party' => 'nullable|string',
            'payment_mode' => 'nullable|string',
            'reference' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.item_name' => 'required|string',
            'items.*.packages' => 'required|integer',
            'items.*.weight' => 'required|numeric',
            'items.*.length' => 'nullable|numeric',
            'items.*.width' => 'nullable|numeric',
            'items.*.height' => 'nullable|numeric',
            'items.*.volume' => 'nullable|numeric',
            'items.*.remarks' => 'nullable|string',
        ]);

        

        // 1️⃣ Save the main shipment arrival
        $arrival = ShipmentArrival::create([
            'shipment_collection_id' => $id,
            'requestId' => $validatedData['requestId'],
            'date_received' => now(), // Or use $validatedData['dateRequested'] if that's the received date
            'verified_by' => Auth::user()->id, // Logged-in user ID
            'cost' => $validatedData['cost'] ?? 0,
            'vat_cost' => $validatedData['vat'] ?? 0,
            'total_cost' => $validatedData['total_cost'] ?? 0,
            'status' => 'Verified', // You can set this dynamically
            'driver_name' => $validatedData['userId'] ?? null,
            'vehicle_reg_no' => $validatedData['vehicleDisplay'] ?? null,
            'remarks' => $validatedData['reference'] ?? null,
        ]);

        // 2️⃣ Loop through shipment items and save into shipment_arrival_items
        // foreach ($validatedData['items'] as $item) {
        //     ShipmentArrivalItem::create([
        //         'shipment_arrival_id' => $arrival->id,
        //         'shipment_item_id' => $item['id'],
        //         'item_name' => $item['item_name'],
        //         'packages' => $item['packages'],
        //         'weight' => $item['weight'],
        //         'length' => $item['length'] ?? 0,
        //         'width' => $item['width'] ?? 0,
        //         'height' => $item['height'] ?? 0,
        //         'volume' => $item['volume'] ?? 0,
        //         'remarks' => $item['remarks'] ?? null,
        //     ]);
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'Shipment arrival and items saved successfully.'
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
