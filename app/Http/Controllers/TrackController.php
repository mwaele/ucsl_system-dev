<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\PdfReportTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLog;

class TrackController extends Controller
{
    
    use PdfReportTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tracking.main');
    }

    // tracking

    // public function getTrackingByRequestId($requestId)
    // {
    //     $track = Track::with('trackingInfos')
    //         ->where('requestId', $requestId)
    //         ->first();

    //     if (!$track) {
    //         return response()->json(['message' => 'Tracking not found'], 404);
    //     }

    //     return response()->json([
    //         'requestId' => $track->requestId,
    //         'tracking_history' => $track->trackingInfos
    //     ]);
    // }
    public function getTrackingByRequestId($requestId, Request $request)
    {
        // Get the main tracking info
        $track = Track::with([
                'client',
                'trackingInfos',
                'clientRequest.user',
                'clientRequest.vehicle',
                'clientRequest.serviceLevel', 
                'clientRequest.client'   
            ])
            ->where('requestId', $requestId)
            ->first();


        // If no track found, return null or 404
        if (!$track) {
            if(auth('client')->user()->name){
                $table = 'clients';
                $id = auth('client')->user()->id;
            }else if(auth('guest')->user()->name){
                $table = 'guests';
                $id = auth('guest')->user()->id;
            }
            UserLog::create([
            'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
            'actions' => 'Tracked Parcel Request ID: '.$requestId. ' and results not found',
            'url' => $request->fullUrl(),
            'reference_id' => $id,
            'table' => $table,
        ]);
            return response()->json(['message' => 'Tracking not found'], 404);
        }

        // Get shipment collection using the same requestId
        $shipment = DB::table('shipment_collections')
            ->where('requestId', $requestId)
            ->first();

        // If shipment exists, fetch origin office and destination name
        if ($shipment) {
            // Get origin office name
            $originOffice = DB::table('offices')
                ->where('id', $shipment->origin_id)
                ->value('name');
                

            // Get destination from rates
            $destinationName = DB::table('rates')
                ->where('id', $shipment->destination_id)
                ->value('destination');

            // get shipment items
            $shipment_items = DB::table('shipment_items')
                ->where('shipment_id', $shipment->id)
                ->get();



            // Attach them to the response
            $track->origin_office = $originOffice;
            $track->destination_name = $destinationName;
            $track->sender_name = $shipment->sender_name;
            $track->receiver_name = $shipment->receiver_name;
            $track->shipment_items = $shipment_items;
        }
        if(auth('client')->user()->name ?? ""){
                $table = 'clients';
                $id = auth('client')->user()->id;
            }else if(auth('guest')->user()->name){
                $table = 'guests';
                $id = auth('guest')->user()->id;
            }
            UserLog::create([
            'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
            'actions' => 'Tracked Parcel Request ID: '.$requestId. ' and results  found',
            'url' => $request->fullUrl(),
            'reference_id' => $id,
            'table' => $table,
        ]);

        $deliveryType = $track->clientRequest->serviceLevel->sub_category_name ?? null;
        $clientType = $track->clientRequest->client->type ?? null;

        $label = '';
        if ($deliveryType && $clientType) {
            $formattedClientType = ucfirst(str_replace('_', ' ', $clientType));
            $label = "($deliveryType Delivery - $formattedClientType Client)";
        }

        $track->tracking_label = $label; 

        return response()->json($track);
    }


    public function generateTrackingPdf($requestId, Request $request)
    {
        $client = Auth::user(); 
        $track = Track::with([
            'client',
            'trackingInfos' => function($query) {
                $query->orderBy('date');
            },
            'clientRequest.client',
            'clientRequest.serviceLevel',
        ])
        ->where('requestId', $requestId)
        ->first();

        $deliveryType = $track->clientRequest->serviceLevel->sub_category_name ?? null;
        $clientType = $track->clientRequest->client->type ?? null;

        $trackingLabel = '';
        if ($deliveryType && $clientType) {
            $formattedClientType = ucfirst(str_replace('_', ' ', $clientType));
            $trackingLabel = "($deliveryType Delivery - $formattedClientType Client)";
        }

        if (!$track) {
            if (auth('client')->user()->name ?? '') {
                $table = 'clients';
                $id = auth('client')->user()->id;
            } else if (auth('guest')->user()->name) {
                $table = 'guests';
                $id = auth('guest')->user()->id;
            }
            UserLog::create([
                'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
                'actions' => 'Tracked Parcel Request ID: '.$requestId. ' to generate pdf and results not found',
                'url' => $request->fullUrl(),
                'reference_id' => $id,
                'table' => $table,
            ]);
            return response()->json(['error' => 'Tracking data not found.'], 404);
        }

        $shipment = DB::table('shipment_collections')
            ->where('requestId', $requestId)
            ->first();

        if ($shipment) {
            $originOffice = DB::table('offices')
                ->where('id', $shipment->origin_id)
                ->value('name');

            $destinationName = DB::table('rates')
                ->where('id', $shipment->destination_id)
                ->value('destination');

            $shipment_items = DB::table('shipment_items')
                ->where('shipment_id', $shipment->id)
                ->get();
        }

        $data = [
            'origin_office' => $originOffice ?? '',
            'destination_name' => $destinationName ?? '',
            'sender_name' => $shipment->sender_name ?? '',
            'receiver_name' => $shipment->receiver_name ?? '',
        ];

        $trackingData = [
            'requestId' => $track->requestId,
            'clientId' => $track->clientId,
            'current_status'=> $track->current_status,
            'tracking_label' => $trackingLabel,
            'client'=>$client,
            'client' => [
                'name' => $track->client->name ?? 'N/A',
                'address' => $track->client->address ?? 'N/A',
                'email' => $track->client->email ?? 'N/A',
                'phone' => $track->client->contactPersonPhone ?? 'N/A',
            ],
            'tracking_infos' => $track->trackingInfos->map(function ($info) {
                return [
                    'date' => $info->date,
                    'details' => $info->details,
                    'remarks' => $info->remarks,
                ];
            })->toArray(),
        ];

        if (auth('client')->user()->name ?? '') {
            $table = 'clients';
            $id = auth('client')->user()->id;
        } else if (auth('guest')->user()->name) {
            $table = 'guests';
            $id = auth('guest')->user()->id;
        }
        UserLog::create([
            'name' => auth('client')->user()->name ?? auth('guest')->user()->name,
            'actions' => 'Tracked Parcel Request ID: '.$requestId. ' and generated pdf report',
            'url' => $request->fullUrl(),
            'reference_id' => $id,
            'table' => $table,
        ]);

        return $this->renderPdfWithPageNumbers(
            'tracking.pdf_report',
            [
                'trackingData' => $trackingData,
                'data' => $data,
                'shipment_items' => $shipment_items,
            ],
            "tracking-report-{$requestId}.pdf",
            'a4',
            'portrait'
        );
    }

    public function showTrackingView($requestId)
    {
        $track = Track::with('trackingInfos')->where('requestId', $requestId)->first();

        if (!$track) {
            abort(404);
        }

        return view('tracking.view', compact('track'));
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
    public function show(Track $track)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        //
    }
}