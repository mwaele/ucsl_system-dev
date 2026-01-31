<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate;
use App\Models\User;
use App\Models\Office;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use App\Models\SubCategory;
use App\Models\DeliveryFailed;
use App\Models\UserLog;
use Auth;

class MyDeliveryController extends Controller
{
    //
    public function show(Request $request )
    {
        $offices = Office::where('id', Auth::user()->station)->get();
        $riders = User::where('role', 'driver')->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();

        $failed_deliveries = DeliveryFailed::all();

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

        $sameDayId = SubCategory::where('sub_category_name', 'Same Day')->value('id');

        $collections = ClientRequest::with([
            'shipmentCollection.office',
            'shipmentCollection.destination',
            'shipmentCollection.items',
            'shipmentCollection.agent'
        ])
        ->where(function ($query) use ($loggedInUserId) {
            $query->where('delivery_rider_id', $loggedInUserId)
                ->orWhere('userId', $loggedInUserId);
        })
        ->where(function ($query) use ($sameDayId) {
            $query->whereIn('status', [
                    'Delivery Rider Allocated',
                    'collected',
                    'delivered'
                ])
                ->orWhere(function ($q) use ($sameDayId) {
                    $q->where('status', 'pending collection')
                    ->where('sub_category_id', $sameDayId);
                });
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // $approvalStatuses = [];

        // foreach ($collections as $collection) {
        //     $shipment = $collection->shipmentCollection;
        //     $approvalStatuses[$collection->requestId] = $shipment?->agent_approved ?? false;
        // }

        $approvalStatuses = $collections->mapWithKeys(function ($collection) {
            $agent = $collection->shipmentCollection?->agent;
            return [$collection->requestId => $agent?->agent_approved ?? false];
        });
        
        $currentModule = 'rider deliveries module';
        $previousModule = session('current_module');

        session(['current_module' => $currentModule]);

        // Log new module access
        UserLog::create([
            'name'    => Auth::user()->name,
            'actions' => 'Accessed ' . $currentModule,
            'table'   => "client_requests",
            'url'     => request()->fullUrl(),
            'user_id' => Auth::id(),
        ]);

        if ($previousModule && $previousModule !== $currentModule) {
            UserLog::create([
                'name'    => Auth::user()->name,
                'actions' => 'Exited ' . $previousModule,
                'url'     => request()->fullUrl(),
                'table'   => "client_requests",
                'user_id' => Auth::id(),
            ]);
        }

        return view('client-request.deliveries', compact(
            'collections',
            'offices',
            'destinations',
            'loggedInUserId',
            'approvalStatuses',
            'riders',
            'grn_no',
            'failed_deliveries'
        ));
    }


}
