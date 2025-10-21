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
use App\Models\OfficeUser;


class ClientPortalJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $clientRequest, $client;

    public function __construct($clientRequest, $client)
    {
        $this->clientRequest = $clientRequest;
        $this->client = $client;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService)
    {
        $requestId = $this->clientRequest->requestId;
        $senderPhone = $this->clientRequest->sender_contact;
        $senderName = $this->clientRequest->sender_name;
        $receiverPhone =$this->clientRequest->receiver_phone;
        $receiverName = $this->clientRequest->receiver_name;
        $clientId = $this->clientRequest->client_id;
        

        Log::info('ClientPortalJob:  Debug', [
            'request_id' => $requestId,
            'request' => $this->clientRequest,
            'client' => $this->client,
        ]);
        $clientName = $this->clientRequest->sender_name;
        
        $trackingUrl = "https://www.ufanisicourier.co.ke/tracking";
        
        $track = "<strong>Tracking Link:</strong> <a href=\"{$trackingUrl}\" target=\"_blank\">Click To Track</a>";
                
                $officeUsers = OfficeUser::where('office_id', $this->clientRequest->origin_id)
                ->with('user:id,email,phone_number,name') // eager load user
                ->get();

                $users = $officeUsers->pluck('user')->filter(); // 
                

                foreach ($users as $user) {
                $recipientName  = $user->name ?? 'User';

                $recipientPhone = $this->formatPhoneNumber($user->phone_number);

                $recipientEmail = $user->email;

                // SMS message
                $smsMsg = "Hello {$recipientName}, {$clientName} has initiated parcel collection request. Act on it: {$requestId}";

                // Email message (with HTML link)
                $emailMsg = "Hello {$recipientName}, {$clientName} has initiated parcel collection request. Act on it: {$requestId}";

                // ✅ Send SMS
                if ($recipientPhone) {
                    $smsService->sendSms(
                        phone: $recipientPhone,
                        subject: 'Parcel Collection Request',
                        message: $smsMsg,
                        addFooter: true
                    );
                }
                // sender email
                $senderSubject = 'Parcel Collection Request';
                //$clientEmail = $client->email;
                $terms = env('TERMS_AND_CONDITIONS', '#'); // fallback if not set
                $footer = "<br><p><strong>Terms & Conditions Applies:</strong> <a href=\"https://www.ufanisicourier.co.ke/terms\" target=\"_blank\">Click here</a></p>
                        <p>Thank you for using Ufanisi Courier Services.</p>";
                $fullSenderMessage = $emailMsg . $footer;

                $emailResponse = EmailHelper::sendHtmlEmail($recipientEmail, $senderSubject, $fullSenderMessage);
            }
    }

    private function formatPhoneNumber($phone)
        {
            $phone = preg_replace('/\D/', '', $phone); // remove non-numeric characters

            if (str_starts_with($phone, '0')) {
                // Replace leading 0 with +254
                return '+254' . substr($phone, 1);
            } elseif (str_starts_with($phone, '254')) {
                // Already has 254, just add +
                return '+' . $phone;
            } elseif (str_starts_with($phone, '+254')) {
                // Already in correct format
                return $phone;
            }

            // fallback: return unchanged
            return $phone;
        }
}
