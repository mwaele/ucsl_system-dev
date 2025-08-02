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

        // Get the latest consignment number
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

        // Get the latest consignment number
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id') // Or use orderByRaw('CAST(SUBSTRING(consignment_no, 4) AS UNSIGNED) DESC') for numeric sort
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 10000; // Start from CN-10000
        }

        $consignment_no = 'CN-' . $newNumber;

        $collections = ClientRequest::with('shipmentCollection.office', 'shipmentCollection.destination', 'shipmentCollection.items')
                            ->whereHas('serviceLevel', function ($query) {
                                $query->where('sub_category_name', 'Same Day');
                            })
                            ->where('userId', $loggedInUserId)
                            ->orderBy('created_at','desc')
                            ->get();
        
        $approvalStatuses = [];

        foreach ($collections as $collection) {
            $shipment = $collection->shipmentCollection;
            $approvalStatuses[$collection->requestId] = $shipment?->agent_approved ?? false;
        }

        return view('client-request.deliveries', compact(
            'collections', 'offices', 'destinations', 'loggedInUserId', 'consignment_no', 'approvalStatuses', 'grn_no'
        ));
    }

}
