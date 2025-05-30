<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function getTrackingByRequestId($requestId)
    {
        // START DUMMY 
        // $dummyTrack = [
        //     'requestId' => 'REQ-12345',
        //     'clientId' => 1,
        //     'client' => [
        //         'name' => 'Saraf Shipping',
        //         'address' => '123 Main Street, Nairobi, Kenya',
        //         'email' => 'info@sarafshipping.com',
        //         'phone' => '+254 712 345678',
        //     ],
        //     'tracking_infos' => [
        //         [
        //             'id' => 1,
        //             'trackId' => 1,
        //             'date' => '2025-05-26 10:00:00',
        //             'details' => 'Client Request Submitted for Collection',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => 'Initial entry',
        //             'created_at' => '2025-05-26 10:00:00',
        //             'updated_at' => '2025-05-26 10:00:00',
        //         ],
        //         [
        //             'id' => 2,
        //             'trackId' => 1,
        //             'date' => '2025-05-27 08:00:00',
        //             'details' => 'Parcel Collected at client premises',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => 'Package picked up successfully',
        //             'created_at' => '2025-05-27 08:00:00',
        //             'updated_at' => '2025-05-27 08:00:00',
        //         ],
        //         [
        //             'id' => 3,
        //             'trackId' => 1,
        //             'date' => '2025-05-28 10:30:00',
        //             'details' => 'Parcel Verified and ready for dispatch',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => '',
        //             'created_at' => '2025-05-28 12:30:00',
        //             'updated_at' => '2025-05-28 12:30:00',
        //         ],
        //         [
        //             'id' => 4,
        //             'trackId' => 1,
        //             'date' => '2025-05-28 17:15:00',
        //             'details' => 'Parcel Dispatched',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => 'Package dispatched',
        //             'created_at' => '2025-05-28 12:30:00',
        //             'updated_at' => '2025-05-28 12:30:00',
        //         ],
        //         [
        //             'id' => 3,
        //             'trackId' => 1,
        //             'date' => '2025-05-28 12:30:00',
        //             'details' => 'Parcel In Transit',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => 'In Transit',
        //             'created_at' => '2025-05-28 12:30:00',
        //             'updated_at' => '2025-05-28 12:30:00',
        //         ],
        //         [
        //             'id' => 4,
        //             'trackId' => 1,
        //             'date' => '2025-05-29 15:00:00',
        //             'details' => 'Parcel Delivered to Destination',
        //             'qty' => null,
        //             'weight' => null,
        //             'volume' => null,
        //             'remarks' => 'Delivered successfully',
        //             'created_at' => '2025-05-29 15:00:00',
        //             'updated_at' => '2025-05-29 15:00:00',
        //         ],
        //     ],
        // ];
        
        // if ($requestId === 'REQ-12345') {
        //     return response()->json($dummyTrack);
        // }
    
        // return response()->json(['message' => 'No tracking found for this request ID.'], 404);
        // // END DUMMY 


        // START REAL 

        $track = Track::with(['client', 'trackingInfos','clientRequest.user','clientRequest.vehicle'])
                ->where('requestId', $requestId)
                ->first();

        if (!$track) {
            return response()->json(['message' => 'Track not found'], 404);
        }
    
            return response()->json($track);

        // END REAL
   
    }

    public function generateTrackingPdf($requestId)
    {
       // DUMMY DATA

        //    if ($requestId === 'REQ-12345') {
        //     $trackingData = [
        //         'requestId' => $requestId,
        //         'clientId' => 1,
        //         'client' => [
        //             'name' => 'Saraf Shipping',
        //             'address' => '123 Main Street, Nairobi, Kenya',
        //             'email' => 'info@sarafshipping.com',
        //             'phone' => '+254 712 345678',
        //         ],
        //         'tracking_infos' => [
        //             [
        //                 'date' => '2025-05-26 10:00:00',
        //                 'details' => 'Client Request Submitted for Collection',
        //                 'remarks' => 'Initial entry',
        //             ],
        //             [
        //                 'date' => '2025-05-27 08:00:00',
        //                 'details' => 'Parcel Collected at client premises',
        //                 'remarks' => 'Picked up successfully',
        //             ],
        //             [
        //                 'date' => '2025-05-27 10:15:00',
        //                 'details' => 'Parcel Verified',
        //                 'remarks' => 'Verified and ready for dispatch',
        //             ],
        //             [
        //                 'date' => '2025-05-27 17:15:00',
        //                 'details' => 'Parcel Dispatched',
        //                 'remarks' => 'Dispatched',
        //             ],
        //             [
        //                 'date' => '2025-05-28 17:30:00',
        //                 'details' => 'Parcel In Transit',
        //                 'remarks' => 'In Transit',
        //             ],
        //             [
        //                 'date' => '2025-05-29 15:00:00',
        //                 'details' => 'Delivered to Destination',
        //                 'remarks' => 'Delivered successfully',
        //             ],
        //         ]
        //     ];
            

        //     $pdf = Pdf::loadView('tracking.pdf_report', compact('trackingData'));
        //     return $pdf->download("tracking-report-{$requestId}.pdf");
        //     }
        //     abort(404);


       // END OF DUMMY DATA

        // START REAL DATA
        $rows = DB::table('tracks')
        ->join('clients', 'clients.id', '=', 'tracks.clientId')
        ->join('tracking_infos', 'tracking_infos.trackId', '=', 'tracks.id')
        ->where('tracks.requestId', $requestId)
        ->select(
            'tracks.requestId',
            'tracks.clientId',
            'clients.name as client_name',
            'clients.address as client_address',
            'clients.email as client_email',
            'clients.contactPersonPhone as client_phone',
            'tracking_infos.date',
            'tracking_infos.details',
            'tracking_infos.remarks'
        )
        ->orderBy('tracking_infos.date')
        ->get();
        if ($rows->isEmpty()) {
            return response()->json(['error' => 'Tracking data not found.'], 404);
        }
        
        $firstRow = $rows->first();
        
        $trackingData = [
            'requestId' => $firstRow->requestId,
            'clientId' => $firstRow->clientId,
            'client' => [
                'name' => $firstRow->client_name,
                'address' => $firstRow->client_address,
                'email' => $firstRow->client_email,
                'phone' => $firstRow->client_phone,
            ],
            'tracking_infos' => [],
        ];
        
        foreach ($rows as $row) {
            $trackingData['tracking_infos'][] = [
                'date' => $row->date,
                'details' => $row->details,
                'remarks' => $row->remarks,
            ];
        }

        //dd($trackingData);

        if (!$trackingData) {
            return back()->with('error', 'No tracking data found for this Request ID.');
        }

        $pdf = Pdf::loadView('tracking.pdf_report' , [
            'trackingData' => $trackingData
        ]);
        return $pdf->download("tracking-report-{$requestId}.pdf");
            // }
        abort(404);

        //END REAL DATA
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
