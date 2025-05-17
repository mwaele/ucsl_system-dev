<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use App\Models\ClientRequest; 

class ShipmentCollectionController extends Controller
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
        //dd($request->all());

        $request->validate([
            'receiverContactPerson' => 'required|string',
            'receiverIdNo' => 'required|string',
            'receiverPhone' => 'required|string',
            'receiverAddress' => 'required|string',
            'receiverTown' => 'required|string',
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'item' => 'required|array',
            'packages' => 'required|array',
            'weight' => 'required|array',
            'length' => 'required|array',
            'width' => 'required|array',
            'height' => 'required|array',
            'volume' => 'required|array',
            'cost' => 'required|numeric',
            'sender_type' => 'required|string',
            'sender_name' => 'required|string',
            'sender_contact' => 'required|string',
            'sender_address' => 'required|string',
            'sender_town' => 'string',
            'sender_id_no' => 'required|string',
            'vat' => 'required|string',
            'total_cost' => 'required|string',
        ]);

        // Save main shipment
        $shipment = ShipmentCollection::create([
            'receiver_name' => $request->receiverContactPerson,
            'receiver_id_no' => $request->receiverIdNo,
            'receiver_phone' => $request->receiverPhone,
            'receiver_address' => $request->receiverAddress,
            'receiver_town' => $request->receiverTown,
            'origin_id' => $request->origin,
            'client_id' => $request->client_id,
            'requestId' => $request->requestId,
            'destination_id' => $request->destination_id,
            'cost' => $request->cost,
            'sender_type' => $request->sender_type,
            'sender_name' => $request->sender_name,
            'sender_contact' => $request->sender_contact,
            'sender_address' => $request->sender_address,
            'sender_town' => $request->sender_town,
            'sender_id_no' => $request->sender_id_no,
            'vat' => $request->vat,
            'total_cost' => $request->total_cost,
            
        ]);

        if($shipment){
        // Update the client_requests table
        ClientRequest::where('requestId', $request->requestId)
        ->update(['status' => 'collected']); // or whatever status you need
         // Save shipment items
         foreach ($request->item as $i => $itemName) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'item_name' => $itemName,
                'packages_no' => $request->packages[$i],
                'weight' => $request->weight[$i],
                'length' => $request->length[$i],
                'width' => $request->width[$i],
                'height' => $request->height[$i],
                'volume' => $request->volume[$i],
            ]);
        }

       
        }

        return back()->with('success', 'Shipment saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentCollection $shipmentCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentCollection $shipmentCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentCollection $shipmentCollection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentCollection $shipmentCollection)
    {
        //
    }
}
