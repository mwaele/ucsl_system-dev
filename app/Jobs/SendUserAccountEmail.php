<?php

namespace App\Jobs;

use App\Helpers\EmailHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendUserAccountEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $plainPassword;

    public function __construct($user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function handle()
    {
        $loginUrl = url('/login');
        $terms = env('TERMS_AND_CONDITIONS', '#');

        $subject = "Your UCS Account Has Been Created";
        $message = "
            Dear {$this->user->name},<br><br>
            Your user account has been created successfully.<br><br>

            Here are your login credentials:<br>
            <strong>Email:</strong> {$this->user->email}<br>
            <strong>Password:</strong> {$this->plainPassword}<br><br>

            You can log in to the UCS Portal using the link below:<br>
            <a href=\"{$loginUrl}\" target=\"_blank\">Login to UCS Portal</a><br><br>

            <p><strong>Terms & Conditions:</strong> <a href=\"{$terms}\" target=\"_blank\">Click here</a></p>
            <p>Thank you for using Ufanisi Courier Services. For we are Fast, Reliable and Secure.</p>
        ";

        EmailHelper::sendHtmlEmail($this->user->email, $subject, $message);
    }
}
