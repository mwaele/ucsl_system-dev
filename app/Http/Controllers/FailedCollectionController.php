<?php

namespace App\Http\Controllers;

use App\Models\FailedCollection;
use Illuminate\Http\Request;
use App\Models\ShipmentCollection;
use App\Models\ClientRequest;
use App\Helpers\EmailHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;    
use App\Models\FailedShipmentCollection;

class FailedCollectionController extends Controller
{
    public function index()
    {
        $records = FailedCollection::latest()->paginate(10);
        return view('failed_collection.index', compact('records'));
    }

    public function create()
    {
        return view('failed_collection.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
            'reference_code' => 'required|string|unique:failed_collections',
            'description' => 'nullable|string',
        ]);

        FailedCollection::create($validated);

        return redirect()->route('failed_collection.index')
            ->with('success', 'Record created successfully.');
    }

    public function show(FailedCollection $FailedCollection)
    {
        return view('failed_collection.show', compact('FailedCollection'));
    }

    public function edit(FailedCollection $FailedCollection)
    {
        return view('failed_collection.edit', compact('FailedCollection'));
    }

    public function update(Request $request, FailedCollection $FailedCollection)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
            'reference_code' => 'required|string|unique:failed_collections,reference_code,' . $FailedCollection->id,
            'description' => 'nullable|string',
        ]);

        $FailedCollection->update($validated);

        return redirect()->route('failed_collection.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(FailedCollection $FailedCollection)
    {
        $FailedCollection->delete();

        return redirect()->route('failed_collection.index')
            ->with('success', 'Record deleted successfully.');
    }
    public function failedCollections(Request $request, $requestId)  
    {
        // Log::info('failed_delivery_alert() called', ['requestId' => $request->input('requestId')]);

        // Extract details;

       // dd($requestId);
        $reason    = $request->input('reason');
        $remarks   = $request->input('remarks');

        $validated = $request->validate([
            'reason' => 'required|string',
            'remarks' => 'required|string',
        ]);

        

        // Validate inputs
        if (!$reason || !$remarks) {
            //Log::warning('Missing details', ['requestId' => $requestId]);
            return response()->json(['status' => 'error', 'message' => 'All details are required.'], 422);
        }

        // Lookup shipment collection
        // $shipmentCollection = ShipmentCollection::where('requestId', $requestId)->first();

        // dd($shipmentCollection);
        // if (!$shipmentCollection) {
        //     //Log::error('ShipmentCollection not found', ['requestId' => $requestId]);
        //     return response()->json(['status' => 'error', 'message' => 'Shipment not found.'], 404);
        // }

        // Lookup client request with relations
        $clientRequest = ClientRequest::with(['client'])
            ->where('requestId', $requestId)
            ->first();

        if (!$clientRequest) {
            Log::error('ClientRequest not found', [
                'requestId' => $requestId
            ]);
            return response()->json(['status' => 'error', 'message' => 'Client Request not found.'], 404);
        }

        // Record failed collection
        $FailedCollection = new FailedShipmentCollection();
        $FailedCollection->requestId = $requestId;
        $FailedCollection->user_id = Auth::id();
        $FailedCollection->failed_collection_id = $reason;
        $FailedCollection->remarks = $remarks;
        $FailedCollection->save();

        DB::table('shipment_collections')
                ->where('requestId', $requestId)
                ->update([
                    'status' => 'collection_failed',
                    'updated_at' => now()
                ]);
        DB::table('client_requests')
                ->where('requestId', $requestId)
                ->update([
                    'status' => 'collection_failed',
                    'updated_at' => now()
                ]);

        try {

            $senderSubject = 'Shipment Collection Failed '.$requestId;
            $stationId = Auth::user()->station;

            $emails = DB::table('office_users')
            ->join('users', 'office_users.user_id', '=', 'users.id')
            ->where('office_users.office_id', $stationId)
            ->pluck('users.email')
            ->toArray(); // ✅ ensure it's a flat array of strings
                    
            Log::info('Preparing Alert Email', ['requestId' => $requestId, 'email' => $emails]);

            $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
            $footer = "<br><p><strong>Terms & Conditions Applies:</strong> <a href=\"https://www.ufanisicourier.co.ke/terms\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";
            $fullSenderMessage = $remarks . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($emails, $senderSubject, $fullSenderMessage);

        } catch (\Throwable $e) {
            Log::error('Failed to send alert email', ['error' => $e->getMessage()]);
        }

        //Log::info('requestApproval() completed', ['requestId' => $requestId]);

        return response()->json(['status' => 'success', 'message' => 'Alert sent.']);


    }
}
