<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SmsService;
use App\Helpers\EmailHelper;
use App\Models\SentMessage;

class SendCollectionNotificationsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $clientRequest, $client, $rider, $vehicle;

    public function __construct($clientRequest, $client, $rider, $vehicle)
    {
        $this->clientRequest = $clientRequest;
        $this->client = $client;
        $this->rider = $rider;
        $this->vehicle = $vehicle;
    }

    public function handle(SmsService $smsService)
    {
        $requestId = $this->clientRequest->requestId;
        $location = $this->clientRequest->collectionLocation;

        // Rider SMS
        $rider_message = "Dear {$this->rider->name}, Collect Parcel for client ({$this->client->name}) {$this->client->contact} Request ID: $requestId at $location";
        $smsService->sendSms($this->rider->phone_number, 'Client Collections Alert', $rider_message, true);

        SentMessage::create([
            'request_id' => $requestId,
            'client_id' => $this->client->id,
            'rider_id' => $this->rider->id,
            'recipient_type' => 'rider',
            'recipient_name' => $this->rider->name,
            'phone_number' => $this->rider->phone_number,
            'subject' => 'Client Collections Alert',
            'message' => $rider_message,
        ]);

        // Rider email
        $footer = "<br><p><strong>Terms & Conditions:</strong> <a href='" . env('TERMS_AND_CONDITIONS', '#') . "' target='_blank'>Click here</a></p>
                   <p>Thank you for using Ufanisi Courier Services for we are <strong>Fast, Reliable and Secure</strong></p>";

        $emailMessage = "Dear {$this->rider->name}, <br><br> Collect Parcel for client ({$this->client->name}) {$this->client->contact} Request ID: $requestId at $location" . $footer;
        EmailHelper::sendHtmlEmail($this->rider->email, 'Client Collections Alert', $emailMessage);

        // Client SMS
        $client_message = "Dear {$this->client->name}, We have allocated {$this->rider->name} {$this->rider->phone_number} to collect your parcel Request ID: $requestId";
        $smsService->sendSms($this->client->contact, 'Parcel Collection Alert', $client_message, true);

        SentMessage::create([
            'request_id' => $requestId,
            'client_id' => $this->client->id,
            'rider_id' => $this->rider->id,
            'recipient_type' => 'client',
            'recipient_name' => $this->client->name,
            'phone_number' => $this->client->contact,
            'subject' => 'Parcel Collection Alert',
            'message' => $client_message,
        ]);

        // Client email
        $emailMessage = "Dear {$this->client->name}, <br><br> We have allocated {$this->rider->name} {$this->rider->phone_number} to collect your parcel Request ID: $requestId" . $footer;
        EmailHelper::sendHtmlEmail($this->client->email, 'Parcel Collection Alert', $emailMessage);
    }
}
