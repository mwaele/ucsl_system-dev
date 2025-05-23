<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        
        $client_requests = ClientRequest::with(['client', 'vehicle', 'user', 'shipmentCollection.items']) // Eager load relations
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
    public function store(Request $request)
    {       
        $client_requests = new ClientRequest();
        $client_requests -> clientId = $request -> clientId;
        $client_requests -> collectionLocation = $request -> collectionLocation;
        $client_requests -> parcelDetails = $request -> parcelDetails;
        $client_requests->dateRequested = Carbon::parse($request->dateRequested)->format('Y-m-d H:i:s');
        $client_requests -> userId = $request -> userId;
        $client_requests -> vehicleId = $request -> vehicleId;
        $client_requests -> requestId = $request -> requestId;
        $client_requests->save();
        
        return redirect()->route('clientRequests.index')->with('Success', 'client request Saved Successfully');
    }

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
