<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class AfricasTalkingService
{
    protected $sms;

    public function __construct()
    {
        $username = config('services.africastalking.username');
        $apiKey = config('services.africastalking.api_key');

        $AT = new AfricasTalking($username, $apiKey);
        $this->sms = $AT->sms();
    }

    public function sendSMS($to, $message)
    {
        try {
            return $this->sms->send([
                'to'      => $to,
                'message' => $message,
                'from'    => config('services.africastalking.from') // Optional sender ID
            ]);
        } catch (\Exception $e) {
            \Log::error('Africa\'s Talking SMS Error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}