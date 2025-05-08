<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        do {
            $request_id = 'REQ-' . mt_rand(10000, 99999);
        } while (ClientRequest::where('requestId', $request_id)->exists());
        $client_requests = Client::all();
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
        $client_requests -> dateRequested = $request->merge([
            'dateRequested' => \Carbon\Carbon::parse($request->dateRequested)->format('Y-m-d H:i:s')
        ]);;
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
    public function update(Request $request, ClientRequest $clientRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientRequest $clientRequest)
    {
        //
    }
}
