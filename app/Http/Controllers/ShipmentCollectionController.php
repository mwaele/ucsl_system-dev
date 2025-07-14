<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCollection;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\TrackingInfo;
use App\Models\ShipmentSubItem;
use App\Services\SmsService;
use App\Models\SentMessage;
use App\Models\Client;
use App\Models\User;
use App\Helpers\EmailHelper;

use Illuminate\Support\Facades\DB;

class ShipmentCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Save walk-in parcel details
     */
    public function create(Request $request, SmsService $smsService)
    {
        DB::beginTransaction();

        try {

            // Generate waybill
            $prefix = 'UCSL';
            $suffix = 'KE';
            $padLength = 10;

            $latestWaybill = DB::table('shipment_collections')
                ->whereNotNull('waybill_no')
                ->orderByDesc('id')
                ->value('waybill_no');

            if ($latestWaybill) {
                // Remove prefix and suffix
                $waybill_no = substr($latestWaybill, strlen($prefix), -strlen($suffix));

                // Convert to int and increment
                $bill_no = (int)$waybill_no + 1;
            } else {
                // First waybill
                $bill_no = 1;
            }

            // Pad with zeros
            $padded_no = str_pad($bill_no, $padLength, '0', STR_PAD_LEFT);

            $waybill_no = $prefix . $padded_no . $suffix;

            // 1. Save ShipmentCollection
            $collection = ShipmentCollection::create([
                'receiver_name' => $request->receiverContactPerson,
                'receiver_id_no' => $request->receiverIdNo,
                'receiver_phone' => $request->receiverPhone,
                'receiver_address' => $request->receiverAddress,
                'receiver_town' => $request->receiverTown,
                'requestId' => $request->requestId,
                'client_id' => $request->clientId,
                'origin_id' => $request->origin_id,
                'destination_id' => $request->destination_id,
                'consignment_no' => $request->consignment_no,
                'waybill_no' => $waybill_no,
                'cost' => $request->cost,
                'vat' => $request->vat,
                'total_cost' => $request->total_cost,
                'collected_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Rebuild items array from flat structure
            $itemCount = count($request->input('item_name', []));
            $totalWeight = 0;
            for ($i = 0; $i < $itemCount; $i++) {
                $itemData = [
                    'item_name' => $request->item_name[$i],
                    'packages' => $request->packages[$i],
                    'weight' => $request->weight[$i],
                    'length' => $request->length[$i],
                    'width' => $request->width[$i],
                    'height' => $request->height[$i],
                    'volume' => $request->volume[$i],
                    'remarks' => $request->remarks[$i] ?? null,
                    'sub_items' => $request->input("items.$i.sub_items", []) // sub-items kept in nested format
                ];

                $totalWeight += $itemData['weight'] * $itemData['packages'];

                // 3. Save each item
                $item = $collection->items()->create([
                    'item_name' => $itemData['item_name'],
                    'packages_no' => $itemData['packages'],
                    'weight' => $itemData['weight'],
                    'length' => $itemData['length'],
                    'width' => $itemData['width'],
                    'height' => $itemData['height'],
                    'volume' => $itemData['volume'],
                    'remarks' => $itemData['remarks'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 4. Save sub-items if any
                if (!empty($itemData['sub_items'])) {
                    foreach ($itemData['sub_items'] as $subItem) {
                        $item->subItems()->create([
                            'item_name' => $subItem['name'],
                            'quantity' => $subItem['quantity'],
                            'weight' => $subItem['weight'],
                            'remarks' => $subItem['remarks'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
             // 5. Save Track
            $trackingId = DB::table('tracks')->insertGetId([
                'requestId' =>  $request->requestId,
                'clientId' => $request->clientId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('tracks')
            ->where('requestId',$request->requestId,)
            ->update([
                'current_status' => 'Awaiting Verification',
                'updated_at' => now()
            ]);

            $text = $itemCount === 1 ? 'item' : 'items';
            $text2 = $totalWeight === 1 ? 'kg' : 'kgs';
            $client = Client::find($request->clientId);
            $clientName = $client ? $client->name : 'Unknown Client';

            // 6. Save Tracking Info
            DB::table('tracking_infos')->insert([
                'trackId' => $trackingId,
                'date' => now(),
                'details' => 'Parcel received from Walk-in Client',
                'remarks' => ''.auth()->user()->name.' received '.$itemCount.' '.$text.' with a total weight of '.$totalWeight.''.$text2.' from '.$clientName.', generated client request ID '.$request->requestId.', with waybill number '.$waybill_no.' and a consignment note with ID '.$request->consignment_no.'',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 6. Save to client_requests table
            DB::table('client_requests')->insert([
                'clientId' => $request->clientId,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'requestId' => $request->requestId,
                'collectionLocation' => null,
                'parcelDetails' => 'Waybill No: '.$waybill_no.', Items: '.$itemCount.', Total Weight: '.$totalWeight.'kg',
                'dateRequested' => now(),
                'userId' => null,
                'vehicleId' => null,
                'created_by' => auth()->id(),
                'office_id' => auth()->user()->station,
                'status' => 'verified',
                'collected_by' => auth()->id(),
                'consignment_no' => $request->consignment_no,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Send receiver SMS
            $waybill = $waybill_no;
            $receiverPhone = $request->receiverPhone;
            $parcelMessage = "Dear Customer, Parcel(#$waybill) has been booked successfully. We will notify when the parcel arrives. Thank you for using Ufanisi Courier Services";

            $smsService->sendSms(
                phone: $receiverPhone,
                subject: 'Parcel Booking Confirmation',
                message: $parcelMessage,
                addFooter: true
            );

            SentMessage::create([
                'request_id' => $request->requestId,
                'client_id' => $request->clientId,
                'recipient_type' => 'receiver',
                'recipient_name' => $request->receiverContactPerson ?? 'Receiver',
                'phone_number' => $receiverPhone,
                'subject' => 'Parcel Booking Confirmation',
                'message' => $parcelMessage,
            ]);

            return redirect()->back()->with('success', 'Shipment collection created and receiver notified successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error saving shipment: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SmsService $smsService)
    { 

        //dd($request);

        $request->validate([
            'receiverContactPerson' => 'required|string',
            'receiverIdNo' => 'required|string',
            'receiverPhone' => 'required|string',
            'receiverAddress' => 'required|string',
            'receiverTown' => 'required|string',
            'origin_id' => 'required',
            'destination_id' => 'required',
            'requestId' => 'required',
            'client_id' => 'required',
            'item_name' => 'required|array',
            'item_name.*' => 'required|string',
            'packages' => 'required|array',
            'weight' => 'required|array',
            'length' => 'required|array',
            'width' => 'required|array',
            'height' => 'required|array',
            'volume' => 'required|array',
            'cost' => 'required|numeric',
            'sender_type' => 'required|string',
            'sender_name' => 'required|string',
            'sender_contact' => 'required|string',
            'sender_address' => 'required|string',
            'sender_town' => 'string',
            'sender_id_no' => 'required|string',
            'vat' => 'required|string',
            'total_cost' => 'required|string',
            'consignment_no' => 'string',
            'base_cost' => 'string',
            'special_rates_state' => 'string',
            'sender_email' => 'string',
            'receiver_email' => 'string',
        ]);
        $consignment_no = $request->consignment_no;
        // Save main shipment
        $shipment = ShipmentCollection::create([
            'receiver_name' => $request->receiverContactPerson,
            'receiver_id_no' => $request->receiverIdNo,
            'receiver_phone' => $request->receiverPhone,
            'receiver_address' => $request->receiverAddress,
            'receiver_town' => $request->receiverTown,
            'origin_id' => $request->origin_id,
            'client_id' => $request->client_id,
            'requestId' => $request->requestId,
            'destination_id' => $request->destination_id,
            'cost' => $request->cost,
            'sender_type' => $request->sender_type,
            'sender_name' => $request->sender_name,
            'sender_contact' => $request->sender_contact,
            'sender_address' => $request->sender_address,
            'sender_town' => $request->sender_town,
            'sender_id_no' => $request->sender_id_no,
            'vat' => $request->vat,
            'total_cost' => $request->total_cost,
            'collected_by' => Auth::user()->id,
            'consignment_no' => $consignment_no,
            'base_cost' => $request->base_cost,
            'sender_email' => $request->senderEmail,
            'receiver_email' => $request->receiverEmail,
            'special_rates_status' => $request->special_rate_state,
            
        ]);
        if($shipment){
            \Log::info('Saving shipment items', [
                'items' => $request->item_name,
                'packages' => $request->packages,
            ]);
            
            // Save shipment items

            $totalWeight = 0;
            $itemCount = 0;

            foreach ($request->item_name as $i => $itemName) {
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'item_name' => $itemName,
                    //'packages_no' => $request->packages[$i],
                    'packages_no' => $request->packages[$i],
                    'weight' => $request->weight[$i],
                    'length' => $request->length[$i],
                    'width' => $request->width[$i],
                    'height' => $request->height[$i],
                    'volume' => $request->volume[$i],
                ]);
                $itemCount++;
                $totalWeight += $request->weight[$i]* $request->packages[$i]; // Make sure this is numeric
            }

            // Update the client_requests table
            ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'collected','collected_by' => Auth::user()->id,
            'consignment_no' => $consignment_no]); // or whatever status you need

            $id = DB::table('tracks')->where('requestId', $request->rqid)->value('id');

            $text = $itemCount === 1 ? 'item' : 'items';
            $text2 = $totalWeight === 1 ? 'kg' : 'kgs';

            DB::table('tracks')
            ->where('requestId',$request->requestId)
            ->update([
                'current_status' => 'Awaiting Verification',
                'updated_at' => now()
            ]);

            // 3. Insert into tracking_infos
            TrackingInfo::create([
                'trackId' => $id,
                'date' => now(),
                'details' => 'Parcel Collected at Client Premises',
                'remarks' => "Rider arrived at client premises for collection; Collected {$itemCount} {$text} with total weight of {$totalWeight} {$text2}. Generated Consignment Note Number {$consignment_no}",
            ]);
         
        }

        // ---------------------------
        // ✅ SMS Notifications Logic
        // ---------------------------
        try {
            $requestId = $request->requestId;
            $consignmentNo = $request->consignment_no;
            $receiverName = $request->receiverContactPerson;
            $receiverPhone = $request->receiverPhone;

            $senderName = $request->sender_name;
            $senderEmail = $request->senderEmail;

            $itemText = $itemCount === 1 ? 'item' : 'items';
            $weightText = $totalWeight === 1 ? 'kg' : 'kgs';

            // Get the front office creator of the client request
            $creator = User::find(ClientRequest::where('requestId', $request->requestId)->value('created_by'));
            $frontOfficeNumber = $creator?->phone_number ?? '+254725525484'; // fallback
            $creatorName = $creator?->name ?? 'Staff';

            // Front Office Message
            $frontMessage = "Parcel has been collected by {$riderName} at client premises. Details: Request ID: {$requestId}; {$itemCount} {$itemText} weighing {$totalWeight} {$weightText}. Consignment No: {$consignmentNo}.";

            $smsService->sendSms(
                phone: $frontOfficeNumber,
                subject: 'Parcel Collected',
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
                'subject' => 'Parcel Collected',
                'message' => $frontMessage,
            ]);

            // front office email
            $office_subject = 'Parcel Collected';
            $office_email = $creator->email;
            $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
            $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
                    <p>Thank you for using Ufanisi Courier Services.</p>";
            $fullOfficeMessage = $frontMessage . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($office_email, $office_subject, $fullOfficeMessage);

            $riderName = Auth::user()->name;

             // sender email
            $senderMessage = "Dear {$senderName}, Your Parcel has been collected by {$riderName}. Details:  Request ID: {$requestId}; {$itemCount} {$itemText} weighing {$totalWeight} {$weightText}. Consignment No: {$consignmentNo}.";
            $sender_subject = 'Parcel Collected';
            $sender_email = $senderEmail;
            $fullOfficeMessage = $senderMessage . $footer;

            $emailResponse = EmailHelper::sendHtmlEmail($sender_email, $sender_subject, $fullOfficeMessage);

            // Receiver Message
            // $receiverMessage = "Dear {$receiverName}, your parcel has been collected from the sender. Consignment No: {$consignmentNo}.";

            // $smsService->sendSms(
            //     phone: $receiverPhone,
            //     subject: 'Parcel Collection Notice',
            //     message: $receiverMessage,
            //     addFooter: true
            // );

            // SentMessage::create([
            //     'request_id' => $requestId,
            //     'client_id' => $request->client_id,
            //     'rider_id' => Auth::id(),
            //     'recipient_type' => 'receiver',
            //     'recipient_name' => $receiverName,
            //     'phone_number' => $receiverPhone,
            //     'subject' => 'Parcel Collection Notice',
            //     'message' => $receiverMessage,
            // ]);

            // receiver  email
            // $receiver_subject = 'Parcel Collection Notice';
            // $receiver_email = $request->receiverEmail;
            // $fullReceiverMessage = $receiverMessage . $footer;

            // $emailResponse = EmailHelper::sendHtmlEmail($receiver_email, $receiver_subject, $fullReceiverMessage);


        } catch (\Exception $e) {
            \Log::error('SMS Notification Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Shipment saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shipment = ShipmentCollection::with('destination')->findOrFail($id);

        return response()->json([
            'waybill_no' => $shipment->waybill_no,
            'destination_name' => $shipment->destination->destination ?? '',
            'weight' => $shipment->weight,
            'packages_no' => $shipment->packages_no,
            // Add more fields as needed
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentCollection $shipmentCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $requestId)
    {
        // Update main shipment details
        $shipment = ShipmentCollection::where('requestId', $requestId)->firstOrFail();

        $shipment->update([
            'actual_cost' => $request->cost,
            'actual_vat' => $request->vat,
            'actual_total_cost' => $request->total_cost,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $prefix = 'UCSL';
        $suffix = 'KE';
        $padLength = 10;

        $latestWaybill = DB::table('shipment_collections')
            ->whereNotNull('waybill_no')
            ->orderByDesc('id')
            ->value('waybill_no');

        if ($latestWaybill) {
            // Remove prefix and suffix
            $waybill_no = substr($latestWaybill, strlen($prefix), -strlen($suffix));

            // Convert to int and increment
            $bill_no = (int)$waybill_no + 1;
        } else {
            // First waybill
            $bill_no = 1;
        }

        // Pad with zeros
        $padded_no = str_pad($bill_no, $padLength, '0', STR_PAD_LEFT);

        $waybill_no = $prefix . $padded_no . $suffix;

        // Update shipment items
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $item = ShipmentItem::find($itemData['id']);

                if ($item) {
                    // Update the main item
                    $item->update([
                        'actual_quantity' => $itemData['packages'],
                        'actual_weight' => $itemData['weight'],
                        'actual_length' => $itemData['length'],
                        'actual_width'  => $itemData['width'],
                        'actual_height' => $itemData['height'],
                        'volume' => $itemData['length'] * $itemData['width'] * $itemData['height'],
                        'remarks' => $itemData['remarks'] ?? null,
                    ]);

                    // Sync sub-items if any
                    if (isset($itemData['sub_items']) && is_array($itemData['sub_items'])) {
                        foreach ($itemData['sub_items'] as $subItemData) {
                            \App\Models\ShipmentSubItem::create([
                                'shipment_item_id' => $item->id,
                                'item_name' => $subItemData['name'],
                                'quantity' => $subItemData['quantity'],
                                'weight' => $subItemData['weight'],
                                'remarks' => $subItemData['remarks'] ?? null,
                                'length' => $subItemData['length'] ?? null,
                                'width' => $subItemData['width'] ?? null,
                                'height' => $subItemData['height'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }

        // Update status in client_requests
        ClientRequest::where('requestId', $request->requestId)
            ->update(['status' => 'verified']);

            ShipmentCollection::where('requestId', $request->requestId)
            ->update(['waybill_no' => $waybill_no]);



            $id = DB::table('tracks')->where('requestId', $request->requestId)->value('id');


            // 3. Insert into tracking_infos
            DB::table('tracking_infos')->insert([
                'trackId' => $id,
                'date' => now(),
                'details' => 'Parcel Verified and ready for dispatch',
                'remarks' => 'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated '.$waybill_no,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Shipment collection verified successfully!');
    }


    public function update_collections(Request $request, $id, SmsService $smsService)
    {
        $shipment = ShipmentCollection::where('requestId', $request->requestId)->firstOrFail();

        if ($shipment) {
            $shipment->update([
                'actual_cost' => $request->cost,
                'actual_vat' => $request->vat,
                'actual_total_cost' => $request->total_cost,
                'billing_party' => $request->billing_party,
                'payment_mode' => $request->payment_mode,
                'reference' => $request->reference,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $item = ShipmentItem::find($itemData['id']);
                    if ($item) {
                        $item->update([
                            'actual_quantity' => $itemData['packages'],
                            'actual_weight' => $itemData['weight'],
                            'actual_length' => $itemData['length'],
                            'actual_width' => $itemData['width'],
                            'actual_height' => $itemData['height'],
                            'actual_volume' => $itemData['length'] * $itemData['width'] * $itemData['height'],
                            'remarks' => $itemData['remarks'] ?? null,
                        ]);
                        if (isset($itemData['sub_items']) && is_array($itemData['sub_items'])) {
                            // Optional: Delete old sub-items if necessary
                            ShipmentSubItem::where('shipment_item_id', $item->id)->delete();

                            foreach ($itemData['sub_items'] as $subItemData) {
                                $item->subItems()->create([
                                    'shipment_item_id' => $item->id,
                                    'item_name' => $subItemData['name'] ?? '',
                                    'quantity' => $subItemData['quantity'] ?? 0,
                                    'weight' => $subItemData['weight'] ?? 0,
                                    'length' => $subItemData['length'] ?? null,
                                    'width' => $subItemData['width'] ?? null,
                                    'height' => $subItemData['height'] ?? null,
                                    'remarks' => $subItemData['remarks'] ?? null,
                                ]);
                            }
                        }
                    }
                }
            }

            // Generate waybill number
            $prefix = 'UCSL';
            $suffix = 'KE';
            $padLength = 10;
            $latestWaybill = DB::table('shipment_collections')
                ->whereNotNull('waybill_no')
                ->orderByDesc('id')
                ->value('waybill_no');

            $bill_no = $latestWaybill
                ? (int)substr($latestWaybill, strlen($prefix), -strlen($suffix)) + 1
                : 1;

            $waybill_no = $prefix . str_pad($bill_no, $padLength, '0', STR_PAD_LEFT) . $suffix;

            // Save waybill
            ShipmentCollection::where('requestId', $request->requestId)
                ->update(['waybill_no' => $waybill_no]);

            // Update client request
            ClientRequest::where('requestId', $request->requestId)
                ->update(['status' => 'verified']);

            // Track update
            DB::table('tracks')
                ->where('requestId', $request->requestId)
                ->update([
                    'current_status' => 'Awaiting Dispatch',
                    'updated_at' => now(),
                ]);

            $trackId = DB::table('tracks')
                ->where('requestId', $request->requestId)
                ->value('id');

            DB::table('tracking_infos')->insert([
                'trackId' => $trackId,
                'date' => now(),
                'details' => 'Parcel Verified and ready for dispatch',
                'remarks' => 'Rider delivered the parcel to the office for verification; Parcel Verified; Waybill Number generated ' . $waybill_no,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ----------------------------
            // ✅ SMS Notifications Logic
            // ----------------------------
            try {
                $senderPhone = $shipment->sender_contact;
                $senderName = $shipment->sender_name;
                $receiverPhone = $shipment->receiver_phone;
                $receiverName = $shipment->receiver_name;
                $clientId = $shipment->client_id;

                // Notify Sender
                $senderMsg = "Hello {$senderName}, your parcel has been verified. Total cost: KES {$shipment->actual_total_cost}. Waybill No: {$waybill_no}. Thank you for choosing UCSL.";
                $smsService->sendSms($senderPhone, 'Parcel Verified', $senderMsg, true);

                SentMessage::create([
                    'request_id' => $request->requestId,
                    'client_id' => $clientId,
                    'rider_id' => auth()->id(),
                    'recipient_type' => 'sender',
                    'recipient_name' => $senderName,
                    'phone_number' => $senderPhone,
                    'subject' => 'Parcel Verified',
                    'message' => $senderMsg,
                ]);

                // Notify Receiver
                $receiverMsg = "Hello {$receiverName}, your parcel has been booked with UCSL. We will notify when the parcel arrives. Waybill No: {$waybill_no}.";
                $smsService->sendSms($receiverPhone, 'Parcel Booked', $receiverMsg, true);

                SentMessage::create([
                    'request_id' => $request->requestId,
                    'client_id' => $clientId,
                    'rider_id' => auth()->id(),
                    'recipient_type' => 'receiver',
                    'recipient_name' => $receiverName,
                    'phone_number' => $receiverPhone,
                    'subject' => 'Parcel Booked',
                    'message' => $receiverMsg,
                ]);
            } catch (\Exception $e) {
                \Log::error('SMS Notification Error (Verification): ' . $e->getMessage());
            }
            
            return redirect()->back()->with('success', 'Shipment and items updated successfully');
        }

        return response()->json(['message' => 'Shipment and items not found']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($requestId)
    {
        //
        //
        $clientRequest = ShipmentCollection::where('requestId', $requestId)->firstOrFail();
        $clientRequest->delete();

        return redirect()->back()->with('success', 'Walk-in parcel deleted successfully.');
    }

    public function receipt($id)
    {

        $request = ShipmentCollection::with(['clientRequest', 'items'])->findOrFail($id);

        return view('receipts.shipment', compact('request'));
    }
}
