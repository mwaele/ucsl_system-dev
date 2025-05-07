<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use Illuminate\Http\Request;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'clientId'=>'required',
            'collectionLocation'=>'required',
            'parcelDetails'=>'required',
            'dateRequested'=>'required',
            'userId'=>'required',
            'vehicleId'=>'required',
            'requestId'=>'required',
        ]);

        $clent_requests = new ClientRequest($validatedData);
        $clent_requests->save();
        
        return redirect()->route('collections.index')->with('Success', 'client request Saved Successfully');
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
