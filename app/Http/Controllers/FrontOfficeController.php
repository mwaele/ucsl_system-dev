<?php

namespace App\Http\Controllers;

use App\Models\FrontOffice;
use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use App\Models\ClientRequest;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontOfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ridersWithPendingVerification = ClientRequest::with(['user', 'vehicle'])
                                        ->where('status', 'collected')
                                        ->select('userId', 'vehicleId', 'status')
                                        ->selectRaw('MIN(dateRequested) as dateRequested') // earliest
                                        ->selectRaw('COUNT(*) as parcel_count')
                                        ->groupBy('userId', 'vehicleId', 'status')
                                        ->get();

        $groupedVerifiedParcels = ClientRequest::with(['user', 'vehicle'])
                                        ->where('status', 'verified')
                                        ->select('userId', 'vehicleId', DB::raw('DATE(dateRequested) as request_date'))
                                        ->selectRaw('COUNT(*) as parcel_count')
                                        ->groupBy('userId', 'vehicleId', DB::raw('DATE(dateRequested)'))
                                        ->orderBy('request_date', 'desc')
                                        ->get()
                                        ->groupBy('request_date');

        $requestsToVerify = ClientRequest::with('client')
                            ->where('status', 'collected')
                            ->get()
                            ->groupBy('userId');
        return view('front-office.index', compact('groupedVerifiedParcels', 'ridersWithPendingVerification', 'requestsToVerify'));
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
