<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMail;

class EmailHelper
{
    public static function sendEmail($email, $subject, $message)
    {
        try {
            Mail::to($email)->send(new GenericMail($subject, $message));
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send failed: ' . $e->getMessage());
            return false;
        }
    }
    public static function sendHtmlEmail($email, $subject, $fullMessage)
    {
        try {
            Mail::mailer('smtp')->send([], [], function ($mail) use ($email, $subject, $fullMessage) {
                $mail->to($email)
                    ->subject($subject)
                    ->html($fullMessage);
            });

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error('Email Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send email.'], 500);
        }
    }

}
