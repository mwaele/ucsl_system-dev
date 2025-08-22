<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\Office;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use Auth;

class MyDeliveryController extends Controller
{
    //
    public function show()
    {
        $offices = Office::where('id', Auth::user()->station)->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();

        // ✅ Correct way: Get all shipment collections for the logged-in user's station & rider
        // $shipment_collections = ShipmentCollection::where('delivery_rider', Auth::user()->station)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        // dd($shipment_collections); // Uncomment to debug

        // Get the latest GRN
        $latestGRN = ShipmentCollection::where('grn_no', 'LIKE', 'GRN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestGRN && preg_match('/GRN-(\d+)/', $latestGRN->grn_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from GRN-10000
        }

        $grn_no = 'GRN-' . $newNumber;
        // dd($grn_no);

        // Get the latest Consignment
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id')
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from CN-10000
        }

        $consignment_no = 'CN-' . $newNumber;

        $collections = ClientRequest::with('shipmentCollection.office', 'shipmentCollection.destination', 'shipmentCollection.items')
            ->where('delivery_rider_id', $loggedInUserId)
            ->orWhere('userId', $loggedInUserId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        
        $approvalStatuses = [];

        foreach ($collections as $collection) {
            $shipment = $collection->shipmentCollection;
            $approvalStatuses[$collection->requestId] = $shipment?->agent_approved ?? false;
        }

        return view('client-request.deliveries', compact(
            'collections',
            'offices',
            'destinations',
            'loggedInUserId',
            'consignment_no',
            'approvalStatuses',
            'grn_no',
        ));
    }


}
