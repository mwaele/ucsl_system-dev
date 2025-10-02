<?php

namespace App\Http\Controllers;

use App\Models\ClientCancelledShipment;
use App\Models\ShipmentCollection;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Helpers\EmailHelper;

use Illuminate\Support\Facades\Log;

class ClientCancelledShipmentController extends Controller
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
    public function show(ClientCancelledShipment $clientCancelledShipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientCancelledShipment $clientCancelledShipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientCancelledShipment $clientCancelledShipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientCancelledShipment $clientCancelledShipment)
    {
        //
    }

    public function cancelRequest(Request $request, $requestId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $shipment = ShipmentCollection::where('requestId', $requestId)->firstOrFail();

        if (!$shipment) {
            return redirect()->back()->with('error', 'Shipment not found.');
        }

        // Create a new cancellation record
        $cancellation = new ClientCancelledShipment();
        $cancellation->requestId = $requestId;
        $cancellation->reason = $request->input('message');
        $cancellation->cancelled_by = auth('client')->user()->id; // Since this is from the client portal
        $cancellation->status = 'pending'; // Initial status
        $cancellation->save();

        // Optionally, you can add logic to notify admins about the cancellation request
        DB::table('shipment_collections')
                ->where('requestId', $requestId)
                ->update([
                    'status' => "Collection Cancelled",
                ]);
        DB::table('client_requests')
                ->where('requestId', $requestId)
                ->update([
                    'status' => "Collection Cancelled",
                ]);
        
        // Track update
            DB::table('tracks')
                ->where('requestId', $requestId)
                ->update([
                    'current_status' => 'Collection Cancelled',
                    'updated_at' => now(),
                ]);

            $trackId = DB::table('tracks')
                ->where('requestId', $requestId)
                ->value('id');

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => 'Client cancelled the shipment collection request.',
                'remarks' => $request->input('message'),
                'created_at' => now(),
                'updated_at' => now()
            ]);


        try {

            $senderSubject = 'Shipment Collection Cancellation -'.$requestId;
            $stationId = $shipment->origin_id;
            Log::info('Checks office id', ['stationId' => $stationId]);

            $emails = DB::table('office_users')
            ->join('users', 'office_users.user_id', '=', 'users.id')
            ->where('office_users.office_id', $stationId)
            ->pluck('users.email')
            ->toArray(); // ✅ ensure it's a flat array of strings
                    
            Log::info('Preparing Alert Email', ['requestId' => $requestId, 'email' => $emails]);

            $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
            $footer = "<br><p><strong>Terms & Conditions Applies:</strong> <a href=\"https://www.ufanisicourier.co.ke/terms\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";
            $fullSenderMessage = $request->message . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($emails, $senderSubject, $fullSenderMessage);
        } catch (\Throwable $e) {
            Log::error('Failed to send alert email', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Cancellation request submitted successfully.');
    }   
}
