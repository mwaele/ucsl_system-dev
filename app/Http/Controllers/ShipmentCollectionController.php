<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\TrackingInfo;

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
     * Save walk-in parcel details
     */
    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Save ShipmentCollection
            $collection = ShipmentCollection::create([
                'receiver_name' => $request->receiverContactPerson,
                'receiver_id_no' => $request->receiverIdNo,
                'receiver_phone' => $request->receiverPhone,
                'receiver_address' => $request->receiverAddress,
                'receiver_town' => $request->receiverTown,
                'requestId' => $request->requestId,
                'client_id' => $request->clientId,
                'origin_id' => $request->origin_id,
                'destination_id' => $request->destination_id,
                'cost' => $request->cost,
                'vat' => $request->vat,
                'total_cost' => $request->total_cost,
                'collected_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Save ShipmentItems
            foreach ($request->items as $itemIndex => $itemData) {
                $item = $collection->items()->create([
                    'item_name' => $itemData['item_name'],
                    'packages_no' => $itemData['packages_no'],
                    'weight' => $itemData['weight'],
                    'length' => $itemData['length'],
                    'width' => $itemData['width'],
                    'height' => $itemData['height'],
                    'volume' => $itemData['volume'],
                    'remarks' => $itemData['remarks'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 3. Save Sub Items if any
                if (isset($itemData['sub_items'])) {
                    foreach ($itemData['sub_items'] as $subItemData) {
                        $item->subItems()->create([
                            'item_name' => $subItemData['name'],
                            'quantity' => $subItemData['quantity'],
                            'weight' => $subItemData['weight'],
                            'remarks' => $subItemData['remarks'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Shipment collection created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving shipment: ' . $e->getMessage());
        }
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
            'consignment_no' => 'string'
        ]);
        $consignment_no = $request->consignment_no;
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
            'collected_by' => Auth::user()->id,
            'consignment_no' => $consignment_no,
            
        ]);

        

        if($shipment){
            \Log::info('Saving shipment items', [
                'items' => $request->item_name,
                'packages' => $request->packages,
            ]);
            
          // Save shipment items

          $totalWeight = 0;
          $itemCount = 0;

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
            $itemCount++;
            $totalWeight += $request->weight[$i]* $request->packages[$i]; // Make sure this is numeric
            }

            // Update the client_requests table
            ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'collected','collected_by' => Auth::user()->id,
            'consignment_no' => $consignment_no]); // or whatever status you need

            $id = DB::table('tracks')->where('requestId', $request->rqid)->value('id');

            $text = $itemCount === 1 ? 'item' : 'items';
            $text2 = $totalWeight === 1 ? 'kg' : 'kgs';

            // 3. Insert into tracking_infos
            TrackingInfo::create([
                'trackId' => $id,
                'date' => now(),
                'details' => 'Parcel Collected at Client Premises',
                'remarks' => "Rider arrived at client premises for collection; Collected {$itemCount} {$text} with total weight of {$totalWeight} {$text2}. Generated Consignment Note Number {$consignment_no}",
            ]);
            
            // DB::table('tracking_infos')->insert([
            //     'trackId' => $id,
            //     'date' => now(),
            //     'details' => 'Parcel Collected at Client Premises',
            //     'remarks' => 'Rider arrived at client premises for collection; Collected ' . $itemCount . ' ' . $text . 
            //                  ' with total weight of ' . $totalWeight . ' ' . $text2 . 
            //                  '. Generated Consignment Note Number ' . $consignment_no,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ]);
            


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

        $prefix = 'UCSL';
        $suffix = 'KE';
        $padLength = 10;

        $latestWaybill = DB::table('shipment_collections')
            ->whereNotNull('waybill_no')
            ->orderByDesc('id')
            ->value('waybill_no');

        if ($latestWaybill) {
            // Remove prefix and suffix
            $waybill_no = substr($latestWaybill, strlen($prefix), -strlen($suffix));

            // Convert to int and increment
            $bill_no = (int)$waybill_no + 1;
        } else {
            // First waybill
            $bill_no = 1;
        }

        // Pad with zeros
        $padded_no = str_pad($bill_no, $padLength, '0', STR_PAD_LEFT);

        $waybill_no = $prefix . $padded_no . $suffix;

        // Update shipment items
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $item = ShipmentItem::find($itemData['id']);

                if ($item) {
                    // Update the main item
                    $item->update([
                        'actual_quantity' => $itemData['packages_no'],
                        'actual_weight' => $itemData['weight'],
                        'actual_length' => $itemData['length'],
                        'actual_width'  => $itemData['width'],
                        'actual_height' => $itemData['height'],
                        'volume' => $itemData['length'] * $itemData['width'] * $itemData['height'],
                        'remarks' => $itemData['remarks'] ?? null,
                    ]);

                    // Sync sub-items if any
                    if (isset($itemData['sub_items']) && is_array($itemData['sub_items'])) {
                        foreach ($itemData['sub_items'] as $subItemData) {
                            \App\Models\ShipmentSubItem::create([
                                'shipment_item_id' => $item->id,
                                'item_name' => $subItemData['name'],
                                'quantity' => $subItemData['quantity'],
                                'weight' => $subItemData['weight'],
                                'remarks' => $subItemData['remarks'] ?? null,
                                'length' => $subItemData['length'] ?? null,
                                'width' => $subItemData['width'] ?? null,
                                'height' => $subItemData['height'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }

        // Update status in client_requests
        ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'verified']);

            ShipmentCollection::where('requestId', $request->requestId)
            ->update(['waybill_no' => $waybill_no]);



            $id = DB::table('tracks')->where('requestId', $request->requestId)->value('id');


            // 3. Insert into tracking_infos
            DB::table('tracking_infos')->insert([
                'trackId' => $id,
                'date' => now(),
                'details' => 'Parcel Verified and ready for dispatch',
                'remarks' => 'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated '.$waybill_no,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return redirect()->route('clientRequests.index')->with('success', 'Shipment collection verified successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($requestId)
    {
        //
        //
        $clientRequest = ShipmentCollection::where('requestId', $requestId)->firstOrFail();
        $clientRequest->delete();

        return redirect()->back()->with('success', 'Walk-in parcel deleted successfully.');
    }

    public function receipt($id)
    {

        $request = ShipmentCollection::with(['clientRequest', 'items'])->findOrFail($id);

        return view('receipts.shipment', compact('request'));
    }
}
