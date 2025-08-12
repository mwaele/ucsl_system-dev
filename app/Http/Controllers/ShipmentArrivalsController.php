<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipmentArrival;
use App\Models\ShipmentArrivalsItem;
use App\Models\ShipmentCollection;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Services\SmsService;
use App\Models\SentMessage;
use App\Helpers\EmailHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pdf;

class ShipmentArrivalsController extends Controller
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
    public function show(string $id)
    {
        
    }

 public function saveArrivals(Request $request, $id,  SmsService $smsService)
    {
        $validatedData = $request->validate([
            'requestId' => 'required|string',
            'dateRequested' => 'nullable|date',
            'cost' => 'nullable|numeric',
            'base_cost' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'vehicleDisplay' => 'nullable|string',
            'userId' => 'nullable|string',
            'billing_party' => 'nullable|string',
            'payment_mode' => 'nullable|string',
            'reference' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.item_name' => 'required|string',
            'items.*.packages' => 'required|integer',
            'items.*.weight' => 'required|numeric',
            'items.*.length' => 'nullable|numeric',
            'items.*.width' => 'nullable|numeric',
            'items.*.height' => 'nullable|numeric',
            'items.*.volume' => 'nullable|numeric',
            'items.*.remarks' => 'nullable|string',
        ]);


        $requestId = $validatedData['requestId'];


        // 1️⃣ Save the main shipment arrival
        $arrival = ShipmentArrival::create([
            'shipment_collection_id' => $id,
            'requestId' => $validatedData['requestId'],
            'date_received' => now(), // Or use $validatedData['dateRequested'] if that's the received date
            'verified_by' => Auth::user()->id, // Logged-in user ID
            'cost' => $validatedData['cost'] ?? 0,
            'vat_cost' => $validatedData['vat'] ?? 0,
            'total_cost' => $validatedData['total_cost'] ?? 0,
            'status' => 'Verified', // You can set this dynamically
            'driver_name' => $validatedData['userId'] ?? null,
            'vehicle_reg_no' => $validatedData['vehicleDisplay'] ?? null,
            'remarks' => $validatedData['reference'] ?? null,
        ]);

        // 2️⃣ Loop through shipment items and save into shipment_arrival_items
        foreach ($validatedData['items'] as $item) {
            ShipmentArrivalsItem::create([
                'shipment_id' => $id,
                'item_name' => $item['item_name'],
                'actual_quantity' => $item['packages'],
                'actual_weight' => $item['weight'],
                'actual_length' => $item['length'] ?? 0,
                'actual_width' => $item['width'] ?? 0,
                'actual_height' => $item['height'] ?? 0,
                'actual_volume' => $item['volume'] ?? 0,
                'remarks' => $item['remarks'] ?? null,
            ]);
        }

        // 3 Update shipment collection status
        $shipment = ShipmentCollection::where('requestId', $requestId)->firstOrFail();
        $shipment->status = 'arrived';
        $shipment->save();

        // 4 Update tracking
         DB::table('tracks')
                ->where('requestId', $requestId)
                ->update([
                    'current_status' => 'Arrived Destination',
                    'updated_at' => now(),
                ]);

            $trackId = DB::table('tracks')
                ->where('requestId', $requestId)
                ->value('id');

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => 'Parcel Arrived, Verified and ready for Collection',
                'remarks' => 'Transporter delivered the parcel at the destination office; Parcel Verified and ready for collection ' ,
                'created_at' => now(),
                'updated_at' => now()
            ]);


        // 5 Send SMS notifications
        try {
                $senderPhone = $shipment->sender_contact;
                $senderName = $shipment->sender_name;
                $receiverPhone = $shipment->receiver_phone;
                $receiverName = $shipment->receiver_name;
                $clientId = $shipment->client_id;
                $waybill_no = $shipment->waybill_no;
                $receiverMail = $shipment->receiver_email;

                // Notify Receiver
                $receiverMsg = "Hello {$receiverName}, your parcel has arrived and is ready for collection. Waybill No: {$waybill_no}. Thank you for choosing UCSL.";
                $smsService->sendSms($receiverPhone, 'Parcel Arrived', $receiverMsg, true);

                SentMessage::create([
                    'request_id' => $requestId,
                    'client_id' => $clientId,
                    'rider_id' => auth()->id(),
                    'recipient_type' => 'receiver',
                    'recipient_name' => $receiverName,
                    'phone_number' => $receiverPhone,
                    'subject' => 'Parcel Arrived',
                    'message' => $receiverMsg,
                ]);
                // front office email
            $subject = 'Parcel Arrived';
            $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
            $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";
            $fullMessage = $receiverMsg . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($receiverMail, $subject, $fullMessage);

            } catch (\Exception $e) {
                \Log::error('SMS Notification Error (Verification): ' . $e->getMessage());
            }



        return response()->json([
            'status' => 'success',
            'message' => 'Shipment arrival and items saved successfully.'
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
