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

        // 1. Get the latest requestId from both tables
        $lastRequestFromClient = ClientRequest::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("CAST(SUBSTRING(requestId, 5) AS UNSIGNED) DESC")
            ->value('requestId');

        $lastRequestFromCollection = ShipmentCollection::where('requestId', 'like', 'REQ-%')
            ->orderByRaw("CAST(SUBSTRING(requestId, 5) AS UNSIGNED) DESC")
            ->value('requestId');

        // 2. Extract numeric parts and determine the highest
        $clientNumber = $lastRequestFromClient ? (int)substr($lastRequestFromClient, 4) : 0;
        $collectionNumber = $lastRequestFromCollection ? (int)substr($lastRequestFromCollection, 4) : 0;

        $nextNumber = max($clientNumber, $collectionNumber) + 1;

        // 3. Format requestId
        $request_id = 'REQ-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $collections = ShipmentCollection::with('client')
                ->whereHas('client', function ($query) {
                    $query->where('type', 'Walkin'); // Only walk-in clients
                })
                ->orderBy('created_at', 'desc')
                ->get();

        // Get the latest consignment number
        $latestConsignment = ShipmentCollection::where('consignment_no', 'LIKE', 'CN-%')
            ->orderByDesc('id') // Or use orderByRaw('CAST(SUBSTRING(consignment_no, 4) AS UNSIGNED) DESC') for numeric sort
            ->first();

        if ($latestConsignment && preg_match('/CN-(\d+)/', $latestConsignment->consignment_no, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 00001; // Start from CN-00001
        }

        $consignment_no = 'CN-' . $newNumber;

        return view('walk-in.index', compact('offices', 'loggedInUserId', 'destinations', 'walkInClients', 'collections', 'request_id', 'consignment_no'));
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
