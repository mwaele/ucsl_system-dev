<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendSms(string $phone, string $subject, string $message, bool $addFooter = false): bool
    {
        $apiKey = config('services.bitwise_sms.api_key');
        $senderId = config('services.bitwise_sms.sender_id');
        $apiUrl = config('services.bitwise_sms.url');
        $terms = config('services.bitwise_sms.terms_and_conditions');

        $footer = "Terms & Conditions: {$terms} \r\n\nFast . Reliable . Secure";

        $payload = [
            'senderID' => $senderId,
            'message'  => "{$subject}\r\n{$message}" . ($addFooter ? "\r\n{$footer}" : ''),
            'phone'    => $phone,
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->post($apiUrl, $payload);

            Log::channel('sms')->info('SMS Sent', ['response' => $response->json()]);

            return true;
        } catch (\Exception $e) {
            Log::channel('sms')->error('SMS Error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
