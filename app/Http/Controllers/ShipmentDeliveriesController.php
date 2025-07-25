<?php

namespace App\Http\Controllers;
use App\Models\ShipmentDeliveries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Tracking;
use App\Models\TrackingInfo;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requestId' => 'required|string|unique:deliveries,requestId',
            'client_id' => 'required|exists:clients,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_id_no' => 'nullable|string|max:20',
            'receiver_type' => 'required|string|max:100',
            'agent_name' => 'nullable|string|max:255',
            'agent_phone' => 'nullable|string|max:20',
            'agent_id_no' => 'nullable|string|max:20',
            'delivery_datetime' => 'required|date',
            'delivered_by' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);
       

         if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $delivery = Delivery::create([
            'requestId' => $request->requestId,
            'client_id' => $request->client_id,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'receiver_id_no' => $request->receiver_id_no,
            'receiver_type' => $request->receiver_type,
            'agent_name' => $request->agent_name,
            'agent_phone' => $request->agent_phone,
            'agent_id_no' => $request->agent_id_no,
            'delivery_datetime' => $request->delivery_datetime,
            'delivered_by' => $request->delivered_by,
            'remarks' => $request->remarks,
            'delivered_by'=> Auth::user()->id,
        ]);

          // 2. Create track
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' => $request->requestId,
                'clientId' => $request->clientId,
                'current_status' => 'Delivered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Create tracking info
            $rider = User::find(Auth::user()->name);
            //$vehicle = Vehicle::find($clientRequest->vehicleId);
            if($request->receiver_type == 'agent'){
                $delivered_to = $request->agent_name;
            }
            if($request->receiver_type == 'client'){
                $delivered_to = $request->receiver_name;
            }


            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Parcel Delivered',
                'user_id' => $rider->id,
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
            $frontMessage = "Parcel has been Delivered by {$riderName} at client premises. Details: Request ID: {$requestId};";

            $smsService->sendSms(
                phone: $frontOfficeNumber,
                subject: 'Parcel Delivered',
                message: $frontMessage,
                addFooter: true
            );

            SentMessage::create([
                'request_id' => $requestId,
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

            $riderName = Auth::user()->name;

             // sender email
            $senderMessage = "Dear {$senderName}, Your Parcel has been delivered by {$riderName}. Details:  Request ID: {$requestId}; ";
            $sender_subject = 'Parcel Delivered';
            $sender_email = $senderEmail;
            $fullOfficeMessage = $senderMessage . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($sender_email, $sender_subject, $fullOfficeMessage);

            


        return redirect()->back()->with('success', 'Delivery inserted successfully.');

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
