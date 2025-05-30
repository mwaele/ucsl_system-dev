<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Tracking;
use App\Models\TrackingInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //Generate Unique request ID
        do {
            $request_id = 'REQ-' . mt_rand(10000, 99999);
        } while (ClientRequest::where('requestId', $request_id)->exists());

        do {
            $consignment_no = 'CN-' . mt_rand(10000, 99999);
        } while (ClientRequest::where('requestId', $request_id)->exists());
        
        $client_requests = ClientRequest::with(['client', 'vehicle', 'user', 'shipmentCollection.items', 'shipmentCollection.items.subItems' ]) // Eager load relations
                            ->orderBy('created_at', 'desc')
                            ->get();
        $clients = Client::all();
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        return view('client-request.index', compact('clients', 'vehicles', 'drivers', 'client_requests', 'request_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //      // Validate incoming request data
    // $validated = $request->validate([
    //     'clientId' => 'required|integer|exists:clients,id',
    //     'collectionLocation' => 'required|string|max:255',
    //     'parcelDetails' => 'required|string',
    //     'dateRequested' => 'required|date',
    //     'vehicleId' => 'required|integer|exists:vehicles,id',
    //     'requestId' => 'required|string|unique:client_requests,requestId',
    // ]);

    // // Wrap in DB transaction to ensure atomicity
    // DB::beginTransaction();

    // try {
    //     // Create client request
    //     $clientRequest = new ClientRequest();
    //     $clientRequest->clientId = $validated['clientId'];
    //     $clientRequest->collectionLocation = $validated['collectionLocation'];
    //     $clientRequest->parcelDetails = $validated['parcelDetails'];
    //     $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
    //     $clientRequest->userId = Auth::user()->id; // Avoid trusting user input
    //     $clientRequest->vehicleId = $validated['vehicleId'];
    //     $clientRequest->requestId = $validated['requestId'];
    //     $clientRequest->save();

    //     // Insert into tracking_table (using Eloquent or Query Builder)
    //     $trackingId = DB::table('tracks')->insertGetId([
    //         'requestId' => $clientRequest->id,   // FK to client_requests
    //         'clientId' => $clientRequest->clientId,
    //         'clientRequestId' => $validated['requestId'],
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);
    //     // Insert into tracking_infos
    //     DB::table('tracking_infos')->insert([
    //         'trackId' => $trackingId,
    //         'date' => now(),
    //         'details' => 'Client Request Submitted for Collection',
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);

    //     DB::commit();

    //     return redirect()->route('clientRequests.index')->with('Success', 'Client request saved and tracking updated.');

    // } catch (\Exception $e) {
    //     DB::rollBack();
    //     return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    // }
        // $validated = $request->validate([
        //     'clientId' => 'required|integer|exists:clients,id',
        //     'collectionLocation' => 'required|string|max:255',
        //     'parcelDetails' => 'required|string',
        //     'dateRequested' => 'required|date',
        //     'userId' => 'required|integer|exists:users,id',
        //     'vehicleId' => 'required|integer|exists:vehicles,id',
        //     'requestId' => 'required|string|unique:client_requests,requestId',
        // ]);

        // $clientRequest = new ClientRequest();
        // $clientRequest->clientId = $validated['clientId'];
        // $clientRequest->collectionLocation = $validated['collectionLocation'];
        // $clientRequest->parcelDetails = $validated['parcelDetails'];
        // $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
        // $clientRequest->userId = $validated['userId'];
        // $clientRequest->vehicleId = $validated['vehicleId'];
        // $clientRequest->requestId = $validated['requestId'];
        // try {
        //    $client_request = $clientRequest->save();

        //    // update tracking
        //    Tracking::create(

        //    )


        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => 'Failed to save client request.']);
        // }

        // return redirect()->route('clientRequests.index')->with('Success', 'Client request saved successfully.');
    //}

    public function store(Request $request)
{
    // Optional: Validate input
    $validated = $request->validate([
        'clientId' => 'required|integer',
        'collectionLocation' => 'required|string',
        'parcelDetails' => 'required|string',
        'dateRequested' => 'required|date',
        'userId' => 'required|integer',
        'vehicleId' => 'required|integer',
        'requestId' => 'required|string|unique:client_requests,requestId',
    ]);

    DB::beginTransaction();

    try {
        // 1. Create ClientRequest
        $clientRequest = new ClientRequest();
        $clientRequest->clientId = $validated['clientId'];
        $clientRequest->collectionLocation = $validated['collectionLocation'];
        $clientRequest->parcelDetails = $validated['parcelDetails'];
        $clientRequest->dateRequested = Carbon::parse($validated['dateRequested'])->format('Y-m-d H:i:s');
        $clientRequest->userId = $validated['userId'];
        $clientRequest->vehicleId = $validated['vehicleId'];
        $clientRequest->requestId = $validated['requestId'];
        $clientRequest->save();

        $userName = User::find($validated['userId'])->name;
        $regNo = Vehicle::find($validated['vehicleId'])->regNo;

        // 2. Insert into tracks table and get inserted ID
        $trackingId = DB::table('tracks')->insertGetId([
            'requestId' =>  $clientRequest->requestId, // This is the DB PK (id), not requestId
            'clientId' => $clientRequest->clientId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Insert into tracking_infos
        DB::table('tracking_infos')->insert([
            'trackId' => $trackingId,
            'date' => now(),
            'details' => 'Client Request Submitted for Collection',
            'user_id' => $validated['userId'],
            'vehicle_id' => $validated['vehicleId'],
            'remarks' => 'Received client collection request, generated client request ID '.$clientRequest->requestId.', allocated '.$userName .' '. $regNo .' for collection',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        return redirect()->route('clientRequests.index')->with('Success', 'Client Request Saved and Tracked Successfully');

    } catch (\Exception $e) {
        DB::rollBack();
    
        \Log::error('Tracking Info Insert Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    
        return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
    
}

    // public function store(Request $request)
    // {       
    //     $client_requests = new ClientRequest();
    //     $client_requests -> clientId = $request -> clientId;
    //     $client_requests -> collectionLocation = $request -> collectionLocation;
    //     $client_requests -> parcelDetails = $request -> parcelDetails;
    //     $client_requests->dateRequested = Carbon::parse($request->dateRequested)->format('Y-m-d H:i:s');
    //     $client_requests -> userId = $request -> userId;
    //     $client_requests -> vehicleId = $request -> vehicleId;
    //     $client_requests -> requestId = $request -> requestId;
    //     $client_requests->save();
        
    //     return redirect()->route('clientRequests.index')->with('Success', 'client request Saved Successfully');
    // }

    /**
     * Display the specified resource.
     */
    public function show(ClientRequest $clientRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientRequest $clientRequest)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $requestId)
    {
        //
        $clientRequest = ClientRequest::where('requestId', $requestId)->firstOrFail();;

        $clientRequest->clientId = $request->clientId;
        $clientRequest->collectionLocation = $request->collectionLocation;
        $clientRequest->dateRequested = $request->dateRequested;
        $clientRequest->userId = $request->userId;
        $clientRequest->vehicleId = $request->vehicleId;
        $clientRequest->parcelDetails = $request->parcelDetails;

        $clientRequest->save();

        return back()->with('success', 'Client request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $clientRequest = ClientRequest::where('requestId', $id)->firstOrFail();
        $clientRequest->delete();

        return back()->with('success', 'Client request deleted successfully.');
    }
}
