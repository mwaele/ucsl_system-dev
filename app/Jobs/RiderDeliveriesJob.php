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
use App\Models\ClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;

class RiderDeliveriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $otp;
    protected $frontOfficeNumber;
    protected $frontMessage;
    protected $creatorName;
    protected $riderName;
    protected $creatorEmail;
    protected $requestId;
    protected $clientId;
    protected $receiverPhone;

    public function __construct(
        $otp,
        $frontOfficeNumber,
        $frontMessage,
        $creatorName,
        $riderName,
        $creatorEmail,
        $requestId,
        $clientId,
        $receiverPhone
    ) {
        $this->otp = $otp;
        $this->frontOfficeNumber = $frontOfficeNumber;
        $this->frontMessage = $frontMessage;
        $this->creatorName = $creatorName;
        $this->riderName = $riderName;
        $this->creatorEmail = $creatorEmail;
        $this->requestId = $requestId;
        $this->clientId = $clientId;
        $this->receiverPhone = $receiverPhone;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService)
    {
        $now = now();

        // ✅ Use $this->otp instead of $otp
        $message = "Your Shipment Collection OTP is {$this->otp}, confirming acceptance of delivery of parcel in good order and condition.";

        // ✅ Send SMS to receiver
        $smsService->sendSms(
            phone: $this->receiverPhone,
            subject: '',
            message: $message,
            addFooter: true
        );

        // ✅ Use $this->requestId and other $this-> variables
        Log::info("Preparing Front Office SMS", ['requestId' => $this->requestId]);

        $smsService->sendSms(
            phone: $this->frontOfficeNumber,
            subject: 'Parcel Delivered',
            message: $this->frontMessage,
            addFooter: true
        );

        Log::info("Front Office SMS Sent", ['requestId' => $this->requestId]);

        // ✅ Prepare email for front office
        $office_subject = 'Parcel Delivered';
        $office_email = $this->creatorEmail;
        $terms = env('TERMS_AND_CONDITIONS', '#');
        $footer = "<br><p><strong>Terms & Conditions Apply:</strong> 
            <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
            <p>Thank you for using Ufanisi Courier Services.</p>";

        $fullOfficeMessage = $this->frontMessage . $footer;

        Log::info("Preparing Front Office Email", ['requestId' => $this->requestId]);

        $emailResponse = EmailHelper::sendHtmlEmail($office_email, $office_subject, $fullOfficeMessage);

        Log::info("Front Office Email Sent", [
            'emailResponse' => $emailResponse,
            'requestId' => $this->requestId
        ]);

        // ✅ Prepare client email
        $senderName = 'Valued Client';
        $senderMessage = "Dear {$senderName}, your parcel has been delivered by {$this->riderName}. 
            Details: Request ID: {$this->requestId};";
        $sender_subject = 'Parcel Delivered';
        $client = Client::find($this->clientId);

        if ($client) {
            $sender_email = $client->email;
        } else {
            $sender_email = null;
            Log::warning("Client Not Found for Sender Email", [
                'requestId' => $this->requestId,
                'clientId' => $this->clientId
            ]);
        }

        $fullClientMessage = $senderMessage . $footer;

        // ✅ Update ClientRequest status
        ClientRequest::where('requestId', $this->requestId)
            ->update(['status' => 'delivered']);

        Log::info("ClientRequest Updated to Delivered", ['requestId' => $this->requestId]);

        // ✅ Send email to client
        Log::info("Preparing Client Email", ['requestId' => $this->requestId]);
        $emailResponse = EmailHelper::sendHtmlEmail($sender_email, $sender_subject, $fullClientMessage);
        Log::info("Client Email Sent", ['emailResponse' => $emailResponse, 'requestId' => $this->requestId]);
    }
}
