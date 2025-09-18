<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendDeliveryNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientRequest, $client, $rider, $vehicleId, $shipment;

    public function __construct($clientRequest, $client, $rider, $vehicleId, $shipment)
    {
        $this->clientRequest = $clientRequest;
        $this->client = $client;
        $this->rider = $rider;
        $this->vehicleId = $vehicleId;
        $this->shipment = $shipment;
    }

    public function handle(SmsService $smsService)
    {
        $now = now();
        $requestId = $this->clientRequest->requestId;

        // Receiver message
        $receiverMsg = "Hello {$this->shipment->receiver_name}, We have re-allocated {$this->rider->name} (phone: {$this->rider->phone_number}) to deliver your parcel {$requestId}. Thank you for choosing UCSL.";
        $smsService->sendSms($this->shipment->receiver_phone, 'Rider Re-Allocated', $receiverMsg, true);

        // Rider message
        $riderMsg = "Hello {$this->rider->name}, You have been allocated to deliver parcel with Request ID: {$requestId}.
        Receiver: {$this->shipment->receiver_name}, Phone: {$this->shipment->receiver_phone}, Address: {$this->shipment->receiver_address}. Please contact the receiver to arrange delivery.";
        $smsService->sendSms($this->rider->phone_number, 'New Delivery Assigned', $riderMsg, true);

        // Store in sent_messages
        DB::table('sent_messages')->insert([
            'request_id' => $requestId,
            'client_id' => $this->shipment->client_id,
            'rider_id' => $this->rider->id,
            'recipient_type' => 'receiver',
            'recipient_name' => $this->shipment->receiver_name,
            'phone_number' => $this->shipment->receiver_phone,
            'subject' => 'Rider Re-Allocated',
            'message' => $receiverMsg,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Email
        $terms = env('TERMS_AND_CONDITIONS', '#');
        $footer = "<br><p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p><p>Thank you for using Ufanisi Courier Services.</p>";
        EmailHelper::sendHtmlEmail($this->shipment->receiver_email, 'Rider Re-Allocated', $receiverMsg . $footer);
    }
}
