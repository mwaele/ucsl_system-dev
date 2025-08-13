<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SmsService;
use App\Helpers\EmailHelper;
use App\Models\SentMessage;

class SendIssueNotificationsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $shipmentCollection, $client, $issuer;

    public function __construct($shipmentCollection, $client, $issuer)
    {
        $this->shipmentCollection = $shipmentCollection;
        $this->client = $client;
        $this->issuer = $issuer;
    }

    public function handle(SmsService $smsService)
    {
        $requestId = $this->shipmentCollection->requestId;

        Log::info('SendIssueNotificationsJob: Sending issue alert', [
            'request_id' => $requestId,
            'client' => $this->client->name ?? null,
            'issuer' => $this->issuer->name ?? null,
        ]);

        $footer = "<br><p><strong>Terms & Conditions:</strong> <a href='" . env('TERMS_AND_CONDITIONS', '#') . "' target='_blank'>Click here</a></p>
                <p>Thank you for using Ufanisi Courier Services for we are <strong>Fast, Reliable and Secure</strong></p>";

        // Sender (Client) SMS
        $clientMessage = "Dear {$this->client->name}, Your parcel Request ID: {$requestId} has been issued to the receiver/agent.";
        $smsService->sendSms($this->client->contact, 'Parcel Issued Alert', $clientMessage, true);

        SentMessage::create([
            'request_id' => $requestId,
            'client_id' => $this->client->id,
            'recipient_type' => 'client',
            'recipient_name' => $this->client->name,
            'phone_number' => $this->client->contact,
            'subject' => 'Parcel Issued Alert',
            'message' => $clientMessage,
        ]);

        // Sender (Client) Email
        $emailMessage = "Dear {$this->client->name}, <br><br>Your parcel Request ID: {$requestId} has been issued to the receiver/agent." . $footer;
        EmailHelper::sendHtmlEmail($this->client->email, 'Parcel Issued Alert', $emailMessage);
    }
}
