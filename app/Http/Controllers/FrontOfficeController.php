<?php

namespace App\Http\Controllers;

use App\Models\FrontOffice;
use App\Models\ShipmentCollection;
use App\Models\Office;
use App\Models\Rate;
use App\Models\Client;
use App\Models\ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class FrontOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $offices = Office::all();
        $loggedInUserId = Auth::user()->id;
        $destinations = Rate::all();
        $walkInClients = Client::where('type', 'Walkin')->get();

        do {
            $request_id = 'REQ-' . mt_rand(10000, 99999);
        } while (
            ClientRequest::where('requestId', $request_id)->exists() ||
            ShipmentCollection::where('requestId', $request_id)->exists()
        );


        $collections = ShipmentCollection::all();
        return view('walk-in.index', compact('offices', 'loggedInUserId', 'destinations', 'walkInClients', 'collections', 'request_id'));
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
    public function show(FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FrontOffice $frontOffice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FrontOffice $frontOffice)
    {
        //
    }
}
