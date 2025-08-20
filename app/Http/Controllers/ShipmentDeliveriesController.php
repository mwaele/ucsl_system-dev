<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentApprovalRequestMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\ShipmentDeliveries;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Tracking;
use App\Models\TrackingInfo;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\ShipmentCollection;
use Illuminate\Support\Facades\DB;
use App\Services\SmsService;
use App\Models\SentMessage;
use Illuminate\Support\Str;
use App\Helpers\EmailHelper;

class ShipmentDeliveriesController extends Controller
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
    public function store(Request $request, SmsService $smsService)
    {
        Log::info("GRN Saved", ['requestId' => $request->requestId, 'grn_no' => $request->grn_no]);

        $delivery = ShipmentDeliveries::create([
            'requestId' => $request->requestId,
            'client_id' => $request->client_id,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'receiver_id_no' => $request->receiver_id_no,
            'receiver_type' => $request->receiver_type,
            'agent_name' => $request->agent_name,
            'agent_phone' => $request->agent_phone,
            'agent_id_no' => $request->agent_id_no,
            'delivery_location' => $request->delivery_location,
            'delivery_datetime' => now(),
            'remark' => $request->remarks,
            'delivered_by'=> Auth::user()->id,
        ]);

        // 2. Create goods received note number
        ShipmentCollection::where('requestId', $request->requestId)
            ->update(['grn_no' => $request->grn_no]);

        // 3. Insert or update tracking record
        $existingTrack = DB::table('tracks')->where('requestId', $request->requestId)->first();

        if ($existingTrack) {
            DB::table('tracks')
                ->where('id', $existingTrack->id)
                ->update([
                    'current_status' => 'Delivered',
                    'updated_at' => now(),
                ]);
            $trackingId = $existingTrack->id;
        } else {
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' => $request->requestId,
                'clientId' => $request->client_id,
                'current_status' => 'Delivered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Create tracking info
        $riderName = Auth::user()->name;
        $riderId = Auth::id();
        $receiverTown = DB::table('shipment_collections')
            ->where('requestId', $request->requestId)
            ->value('receiver_town');

        $deliveredTo = match ($request->receiver_type) {
            'agent' => $request->agent_name,
            'receiver' => $request->receiver_name,
            default => 'Unknown Receiver'
        };

        DB::table('tracking_infos')->insert([
            'trackId' => $trackingId,
            'date' => now(),
            'details' => 'Parcel Delivered and GRN Created',
            'user_id' => $riderId,
            'remarks' => "Parcel with request ID {$request->requestId} was delivered to {$deliveredTo} at {$request->delivery_location}, {$receiverTown} by rider {$riderName}. GRN No: {$request->grn_no}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Tracking Updated for Delivery', [
            'requestId' => $request->requestId,
            'deliveredTo' => $deliveredTo,
            'deliveredBy' => $riderName,
            'grn_no' => $request->grn_no,
        ]);


        $riderName = Auth::user()->name;

        // Get the front office creator of the client request
        $creator = User::find(ClientRequest::where('requestId', $request->requestId)->value('created_by'));
        $frontOfficeNumber = $creator?->phone_number ?? '+254725525484'; // fallback
        $creatorName = $creator?->name ?? 'Staff';

        // Front Office Message
        $frontMessage = "Parcel has been Delivered by {$riderName} at client premises. Details: Request ID: {$request->requestId};";

        $smsService->sendSms(
            phone: $frontOfficeNumber,
            subject: 'Parcel Delivered',
            message: $frontMessage,
            addFooter: true
        );

        SentMessage::create([
            'request_id' => $request->requestId,
            'client_id' => $request->client_id,
            'rider_id' => Auth::id(),
            'recipient_type' => 'staff',
            'recipient_name' => $creatorName ?? 'Front Office',
            'phone_number' => $frontOfficeNumber,
            'subject' => 'Parcel Delivered',
            'message' => $frontMessage,
        ]);

        // front office email
        $office_subject = 'Parcel Delivered';
        $office_email = $creator->email;
        $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
        $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                <p>Thank you for using Ufanisi Courier Services.</p>";
        $fullOfficeMessage = $frontMessage . $footer;

        $emailResponse = EmailHelper::sendHtmlEmail($office_email, $office_subject, $fullOfficeMessage);
    
        $senderName = 'Valued Client';
        $riderName = Auth::user()->name;

            // sender email
        $senderMessage = "Dear {$senderName}, Your Parcel has been delivered by {$riderName}. Details:  Request ID: {$request->requestId}; ";
        $sender_subject = 'Parcel Delivered';
        $client = Client::find($request->client_id); // or whatever key you have

            if ($client) {
                $sender_email = $client->email; // or whatever column name is used for the email
            } else {
                // handle case where client isn't found
                $sender_email = null; // or throw an error / log
            }
        $fullOfficeMessage = $senderMessage . $footer;

        ClientRequest::where('requestId', $request->requestId)
        ->update(['status' => 'delivered']);
        $emailResponse = EmailHelper::sendHtmlEmail($sender_email, $sender_subject, $fullOfficeMessage);

        return redirect()->back()->with('success', 'Delivery inserted successfully.');

    }

    public function handleAgentApproval(Request $request)
    {
        $requestId = $request->input('request_id');
        $action = $request->input('action');
        $remarks = $request->input('remarks');

        $collection = ShipmentCollection::where('requestId', $requestId)->first();

        if (!$collection) {
            return redirect()->back()->with('error', 'Shipment not found.');
        }

        if ($action === 'approve') {
            $collection->agent_approved = true;
            $collection->approval_remarks = null; // optional: clear remarks
        } elseif ($action === 'decline') {
            $collection->agent_approved = false;
            $collection->agent_requested = false;
            $collection->approval_remarks = $remarks; // save reason
        }

        $collection->save();

        // Add tracking info
        $trackId = DB::table('tracks')->where('requestId', $requestId)->value('id');

        if ($trackId) {
            DB::table('tracks')
                ->where('id', $trackId)
                ->update([
                    'current_status' => $action === 'approve' ? 'Agent Approved' : 'Agent Declined',
                    'updated_at' => now()
                ]);

            $userName = Auth::user()->name;

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => $action === 'approve' ? 'Agent Approval Granted' : 'Agent Approval Declined',
                'user_id' => Auth::id(),
                'remarks' => $action === 'approve' 
                    ? "Agent request was approved by $userName."
                    : "Agent request declined by $userName. Remarks: $remarks",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Agent request has been ' . ($action === 'approve' ? 'approved' : 'declined') . '.');
    }

    public function requestApproval(Request $request) 
    {
        // Log entry point with request data
        Log::info('requestApproval() called', ['requestId' => $request->input('requestId')]);

        // Extract agent details
        $requestId   = $request->input('requestId');
        $agentName   = $request->input('agentName');
        $agentId     = $request->input('agentId');
        $agentPhone  = $request->input('agentPhone');
        $agentReason = $request->input('agentReason');

        // Validate inputs
        if (!$agentName || !$agentId || !$agentPhone || !$agentReason) {
            Log::warning('Missing agent details', ['requestId' => $requestId]);
            return response()->json(['status' => 'error', 'message' => 'Agent details are required.'], 422);
        }

        // Lookup shipment collection
        $shipmentCollection = ShipmentCollection::where('requestId', $requestId)->first();
        if (!$shipmentCollection) {
            Log::error('ShipmentCollection not found', ['requestId' => $requestId]);
            return response()->json(['status' => 'error', 'message' => 'Shipment not found.'], 404);
        }

        // Lookup client request with relations
        $clientRequest = ClientRequest::with(['client', 'serviceLevel'])
            ->where('requestId', $requestId)
            ->first();

        if (!$clientRequest) {
            Log::error('ClientRequest not found', [
                'shipmentCollectionId' => $shipmentCollection->id,
                'requestId' => $requestId
            ]);
            return response()->json(['status' => 'error', 'message' => 'Client Request not found.'], 404);
        }

        // Normalize clientType and serviceLevel to match route names
        $clientType   = strtolower($clientRequest->client->type ?? 'unknown');
        $serviceLevel = strtolower($clientRequest->serviceLevel->sub_category_name ?? 'unknown');

        $serviceLevel = Str::slug($serviceLevel, '');  // e.g. "same day" → "sameday"
        $clientType   = str_replace('_', '-', $clientType); // e.g. "on_account" → "on-account"
        if ($clientType === 'walkin') {
            $clientType = 'walk-in';
        }

        // Build approval route
        $routeName   = $serviceLevel . '.' . $clientType;
        $approvalUrl = route($routeName) . '?requestId=' . $requestId;
        Log::info('Approval URL generated', ['routeName' => $routeName]);

        // Update shipment status
        $shipmentCollection->agent_approved  = false;
        $shipmentCollection->agent_requested = true;
        $shipmentCollection->save();
        Log::info('ShipmentCollection updated', ['id' => $shipmentCollection->id]);

        // Update tracking info
        $trackId = DB::table('tracks')->where('requestId', $requestId)->value('id');
        if ($trackId) {
            DB::table('tracks')
                ->where('id', $trackId)
                ->update([
                    'current_status' => 'Agent Approval Requested',
                    'updated_at' => now()
                ]);

            DB::table('tracking_infos')->insert([
                'trackId'    => $trackId,
                'date'       => now(),
                'details'    => 'Agent Approval Request Submitted',
                'user_id'    => Auth::id(),
                'remarks'    => "Agent Name: {$agentName}, ID: {$agentId}, Phone: {$agentPhone}, Reason: {$agentReason}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Tracking updated', ['trackId' => $trackId]);
        }

        // Send approval email
        try {
            Mail::to('jeff.letting@ufanisi.co.ke')
            ->cc('mwaele@ufanisi.co.ke')
            ->send(
                new AgentApprovalRequestMail(
                    $requestId,
                    $agentName,
                    $agentId,
                    $agentPhone,
                    $agentReason,
                    $approvalUrl
                )
            );
            Log::info('Approval email sent');
        } catch (\Throwable $e) {
            Log::error('Failed to send approval email', ['error' => $e->getMessage()]);
        }

        Log::info('requestApproval() completed', ['requestId' => $requestId]);

        return response()->json(['status' => 'success', 'message' => 'Approval request sent.']);
    }

    public function approveAgent($requestId)
    {
        $shipmentCollection = ShipmentCollection::where('requestId', $requestId)->first();

        if (!$shipmentCollection) {
            return redirect('/')->with('error', 'Shipment not found.');
        }

        // Mark as approved
        $shipmentCollection->agent_approved = true;
        $shipmentCollection->approved_at = now();
        $shipmentCollection->approved_by = Auth::id();
        $shipmentCollection->save();

        // Add tracking info
        $trackId = DB::table('tracks')->where('requestId', $requestId)->value('id');

        if ($trackId) {
            DB::table('tracks')
                ->where('id', $trackId)
                ->update([
                    'current_status' => 'Agent Approved for Delivery',
                    'updated_at' => now()
                ]);

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => 'Agent Approved for Final Delivery',
                'user_id' => Auth::id(),
                'remarks' => "Agent was approved by " . Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('dashboard')->with('success', "Agent approved for delivery ID $requestId.");
    }

    public function showDeclineForm($requestId)
    {
        return view('client-request.decline', compact('requestId'));
    }

    public function submitDecline(Request $request, $requestId)
    {
        $request->validate([
            'remarks' => 'required|string|max:500',
        ]);

        $shipment = ShipmentCollection::where('requestId', $requestId)->firstOrFail();
        $shipment->agent_approved = false;
        $shipment->agent_decline_remarks = $request->remarks;
        $shipment->save();

        // Add tracking info
        $trackId = DB::table('tracks')->where('requestId', $requestId)->value('id');

        if ($trackId) {
            DB::table('tracks')
                ->where('id', $trackId)
                ->update([
                    'current_status' => 'Agent Approval Declined',
                    'updated_at' => now()
                ]);

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => 'Agent Approval Declined',
                'user_id' => Auth::id(),
                'remarks' => "Decline remarks: " . $request->remarks,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Optionally notify agent or log action...

        return redirect()->route('dashboard')->with('status', 'Request declined with remarks.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipmentDeliveries $shipmentDeliveries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentDeliveries $shipmentDeliveries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentDeliveries $shipmentDeliveries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentDeliveries $shipmentDeliveries)
    {
        //
    }
}
