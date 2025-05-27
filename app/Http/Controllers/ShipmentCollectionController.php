<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Auth;

use Illuminate\Support\Facades\DB;

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

        $request->validate([
            'receiverContactPerson' => 'required|string',
            'receiverIdNo' => 'required|string',
            'receiverPhone' => 'required|string',
            'receiverAddress' => 'required|string',
            'receiverTown' => 'required|string',
            'origin_id' => 'required',
            'destination_id' => 'required',
            'requestId' => 'required',
            'client_id' => 'required',
            'item_name' => 'required|array',
            'item_name.*' => 'required|string',
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
            'origin_id' => $request->origin_id,
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
            \Log::info('Saving shipment items', [
                'items' => $request->item_name,
                'packages' => $request->packages,
            ]);
            
          // Save shipment items
          foreach ($request->item_name as $i => $itemName) {
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'item_name' => $itemName,
                //'packages_no' => $request->packages[$i],
                'packages_no' => $request->packages[$i],
                'weight' => $request->weight[$i],
                'length' => $request->length[$i],
                'width' => $request->width[$i],
                'height' => $request->height[$i],
                'volume' => $request->volume[$i],
            ]);
            }

            // Update the client_requests table
            ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'collected']); // or whatever status you need

             // 2. Insert into tracks table and get inserted ID
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' =>  $request->rqid, // This is the DB PK (id), not requestId
                'clientId' => $request->cid,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 3. Insert into tracking_infos
            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Parcel Collected at client premises',
                'remarks' => 'Initial entry',
                'created_at' => now(),
                'updated_at' => now()
            ]);


        // $shipment_collections = ShipmentCollection::with('shipment_items')->findOrFail($shipment->id);
        // $pdf = Pdf::loadView('receipts.collection_receipt', compact('shipment_collections'))
        // ->setPaper([0, 0, 226.77, 600], 'portrait'); // 80mm wide receipt

        // return $pdf->stream("receipt_{$shipment->id}.pdf");
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
    public function update(Request $request, $requestId)
    {
        // Update main shipment details
        $shipment = ShipmentCollection::where('requestId', $requestId)->firstOrFail();

        $shipment->update([
            'actual_cost' => $request->base_cost,
            'actual_vat' => $request->vat,
            'actual_total_cost' => $request->total_cost,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update shipment items
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $item = ShipmentItem::find($itemData['id']);
                if ($item) {
                    $item->update([
                        'actual_quantity' => $itemData['packages_no'],
                        'actual_weight' => $itemData['weight'],
                        'actual_length' => $itemData['length'],
                        'actual_width'  => $itemData['width'],
                        'actual_height' => $itemData['height'],
                        'volume' => $itemData['length'] * $itemData['width'] * $itemData['height'],
                        'remarks' => $itemData['remarks'] ?? null,
                    ]);
                }
            }
        }

        // Update status in client_requests
        ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'verified']);

        return redirect()->route('clientRequests.index')->with('success', 'Shipment collection verified successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentCollection $shipmentCollection)
    {
        //
    }

    public function receipt($id)
    {

        $request = ShipmentCollection::with(['clientRequest', 'items'])->findOrFail($id);

        return view('receipts.shipment', compact('request'));
    }
}
