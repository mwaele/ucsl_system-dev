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
use Illuminate\Foundation\Bus\Dispatchable;

class SendIssueNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shipmentCollection;
    public $client;
    public $issuer;
    public $receiverName;
    public $receiverPhone;
    public $otp;

    /**
     * Create a new job instance.
     */
    public function __construct($shipmentCollection, $client, $issuer, $receiverName, $receiverPhone, $otp)
    {
        $this->shipmentCollection = $shipmentCollection;
        $this->client = $client;
        $this->issuer = $issuer;
        $this->receiverName = $receiverName;
        $this->receiverPhone = $receiverPhone;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
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
        $clientMessage = "Dear {$this->client->name}, Your parcel Request ID: {$requestId} has been issued to {$this->receiverName} - {$this->receiverPhone}.";
        $smsService->sendSms($this->client->contact, 'Parcel Issued Alert', $clientMessage, true);

        // Receiver SMS
        if ($this->receiverPhone) {
            $receiverMessage = "Dear {$this->receiverName}, Your parcel Collection OTP is : {$this->otp}";
            $smsService->sendSms($this->receiverPhone, 'Parcel Collection OTP', $receiverMessage, true);

            // Log receiver SMS
            SentMessage::create([
                'request_id' => $requestId,
                'client_id' => $this->client->id,
                'recipient_type' => 'receiver',
                'recipient_name' => $this->receiverName,
                'phone_number' => $this->receiverPhone,
                'subject' => 'Parcel Issued Alert',
                'message' => $receiverMessage,
            ]);
        }

        // Log client SMS
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
        $emailMessage = "Dear {$this->client->name}, <br><br>Your parcel Request ID: {$requestId} has been issued to {$this->receiverName} - {$this->receiverPhone}." . $footer;
        EmailHelper::sendHtmlEmail($this->client->email, 'Parcel Issued Alert', $emailMessage);
    }
}
