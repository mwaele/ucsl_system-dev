<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentApprovalRequestMail;
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
        

        $delivery = ShipmentDeliveries::create([
            'requestId' => $request->requestId,
            'client_id' => $request->client_id,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'receiver_id_no' => $request->receiver_id_no,
            'agent_name' => $request->agent_name,
            'agent_phone' => $request->agent_phone,
            'agent_id_no' => $request->agent_id_no,
            'delivery_location' => $request->delivery_location,
            'delivery_datetime' => now(),
            'remark' => $request->remarks,
            'delivered_by'=> Auth::user()->id,
        ]);

          // 2. Create track
        $trackingId = DB::table('tracks')->insertGetId([
            'requestId' => $request->requestId,
            'clientId' => $request->client_id,
            'current_status' => 'Delivered',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create tracking info
        $goods_received_note_no = $request->grn_no;
        
        ShipmentCollection::where('requestId', $request->requestId)
        ->update(['grn_no' => $goods_received_note_no]); 

        // 4. Create tracking info
        $rider = User::find(Auth::user()->name);
        //$vehicle = Vehicle::find($clientRequest->vehicleId);
        $delivered_to = 'Unknown Receiver'; // default fallback

        if ($request->receiver_type == 'agent') {
            $delivered_to = $request->agent_name;
        } elseif ($request->receiver_type == 'client') {
            $delivered_to = $request->receiver_name;
        }

        DB::table('tracking_infos')->insert([
            'trackId' => $trackingId,
            'date' => now(),
            'details' => 'Parcel Delivered',
            'user_id' => Auth::user()->id,
            //'vehicle_id' => $vehicle->id,
            'remarks' => "Parcel has been delivered to {$delivered_to} request ID {$request->requestId}. Delivered by {$rider}",
            'created_at' => now(),
            'updated_at' => now(),
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

        return redirect()->back()->with('success', 'Agent request has been ' . ($action === 'approve' ? 'approved' : 'declined') . '.');
    }

    public function requestApproval(Request $request)
    {
        $requestId = $request->input('requestId');
        $agentName = $request->input('agentName');
        $agentId = $request->input('agentId');
        $agentPhone = $request->input('agentPhone');
        $agentReason = $request->input('agentReason');

        // Validate inputs (optional)
        if (!$agentName || !$agentId || !$agentPhone || !$agentReason) {
            return response()->json(['status' => 'error', 'message' => 'Agent details are required.'], 422);
        }

        $shipmentCollection = ShipmentCollection::where('requestId', $requestId)->first();

        if (!$shipmentCollection) {
            return response()->json(['status' => 'error', 'message' => 'Shipment not found.'], 404);
        }

        $shipmentCollection->agent_approved = false;
        $shipmentCollection->agent_requested = true;
        $shipmentCollection->save();

        // Send approval email (you may customize the mailable to include agent details)
        Mail::to('mwaele@ufanisi.co.ke')->send(
            new AgentApprovalRequestMail($requestId, $agentName, $agentId, $agentPhone, $agentReason)
        );

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
