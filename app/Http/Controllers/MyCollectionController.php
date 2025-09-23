<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Models\Rate;
use App\Models\Office;
use App\Models\ShipmentCollection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PdfReportTrait;
use Carbon\Carbon;

use Auth;

class MyCollectionController extends Controller
{
    use PdfReportTrait;
    
    public function show()
    {
        $offices = Office::where('id', Auth::user()->station)->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();

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

        $collections = ClientRequest::with('shipmentCollection.office',
                                            'shipmentCollection.destination',
                                            'shipmentCollection.items')
            ->where('userId', $loggedInUserId)
            ->where('source', 'ucsl')
            ->whereHas('serviceLevel', function ($query) {
                $query->where('sub_category_name', 'Overnight');
            })
            ->orderBy('created_at','desc')
            ->get();
        return view('client-request.show')->with(['collections'=>$collections,'offices'=>$offices,'destinations'=>$destinations, 'loggedInUserId'=>$loggedInUserId, 'consignment_no'=> $consignment_no]);
    }

    public function collect()
    {
        $offices = Office::where('id', Auth::user()->station)->get();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();

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

        $collections = ClientRequest::with('shipmentCollection.office',
                                            'shipmentCollection.destination',
                                            'shipmentCollection.items')
            ->where('userId', $loggedInUserId)
            ->where('source', 'client_portal')
            ->orderBy('created_at','desc')
            ->get();
        return view('client-request.client-portal-rider-collections')->with(['collections'=>$collections,'offices'=>$offices,'destinations'=>$destinations, 'loggedInUserId'=>$loggedInUserId, 'consignment_no'=> $consignment_no]);
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'receiverContactPerson' => 'required|string',
            'receiverIdNo' => 'required|string',
            'receiverPhone' => 'required|string',
            'receiverAddress' => 'required|string',
            'receiverTown' => 'required|string',
            'origin' => 'required',
            'destination' => 'required|string',
            'cost' => 'required|numeric',
            'item' => 'required|array',
            'packages' => 'required|array',
            'weight' => 'required|array',
            'length' => 'required|array',
            'width' => 'required|array',
            'height' => 'required|array',
            'volume' => 'required|array',
        ]);

        // Save the main shipment
        $shipment = Shipment::create([
            'receiver_contact_person' => $request->receiverContactPerson,
            'receiver_id_no' => $request->receiverIdNo,
            'receiver_phone' => $request->receiverPhone,
            'receiver_address' => $request->receiverAddress,
            'receiver_town' => $request->receiverTown,
            'origin' => $request->origin,
            'destination' => $request->destination,
            'cost' => $request->cost
        ]);

        // Save multiple items
        // foreach ($request->item as $index => $itemName) {
        //     ShipmentItem::create([
        //         'shipment_id' => $shipment->id,
        //         'item_name' => $itemName,
        //         'packages' => $request->packages[$index],
        //         'weight' => $request->weight[$index],
        //         'length' => $request->length[$index],
        //         'width' => $request->width[$index],
        //         'height' => $request->height[$index],
        //     ]);
        // }

        return redirect()->back()->with('success', 'Shipment saved successfully!');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'receiverContactPerson' => 'required|string',
    //         'receiverIdNo' => 'required|string',
    //         'receiverPhone' => 'required|string',
    //         'receiverAddress' => 'required|string',
    //         'receiverTown' => 'required|string',
    //         'origin' => 'required|integer',
    //         'destination' => 'required|integer',
    //         'item' => 'required|array',
    //         'packages' => 'required|array',
    //         'weight' => 'required|array',
    //         'length' => 'required|array',
    //         'width' => 'required|array',
    //         'height' => 'required|array',
    //         'cost' => 'required|numeric',
    //     ]);

    //     // Save main shipment
    //     $shipment = Shipment::create([
    //         'receiver_name' => $request->receiverContactPerson,
    //         'receiver_id_no' => $request->receiverIdNo,
    //         'receiver_phone' => $request->receiverPhone,
    //         'receiver_address' => $request->receiverAddress,
    //         'receiver_town' => $request->receiverTown,
    //         'origin_id' => $request->origin,
    //         'destination_id' => $request->destination,
    //         'cost' => $request->cost,
    //     ]);

    //     // Save shipment items
    //     foreach ($request->item as $i => $itemName) {
    //         ShipmentItem::create([
    //             'shipment_id' => $shipment->id,
    //             'item_name' => $itemName,
    //             'packages' => $request->packages[$i],
    //             'weight' => $request->weight[$i],
    //             'length' => $request->length[$i],
    //             'width' => $request->width[$i],
    //             'height' => $request->height[$i],
    //         ]);
    //     }

    //     return back()->with('success', 'Shipment saved successfully!');
    // }

    public function collections_report()
    {
        $loggedInUserId = Auth::user()->id;

        $collections = ClientRequest::with(
            'shipmentCollection.office',
            'shipmentCollection.destination',
            'shipmentCollection.items'
        )
            ->where('userId', $loggedInUserId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->renderPdfWithPageNumbers(
            'client-request.collections_report',
            ['collections' => $collections],
            'collections_report.pdf',
            'a4',
            'landscape'
        );
    }
}
