<?php

namespace App\Traits;

use App\Http\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait PasswordResetTokens
{
    public function __construct()
    {
    }

    public function passwordReset($otp, $isEmailVerification = false)
    {
        // $sendTo = '*';
        // $type = '*';

        // if ($this->email) {
        //     $sendTo = $this->email;
        //     $type = 'email';
        // } else if ($this->mobile) {
        //     $sendTo = $this->moble;
        //     $type = 'mobile';
        // }

        $tokenData = $this->passwordResetToken()->updateOrCreate(
            [
                'send_to' => $this->email,
                'type' => 'app'

            ],
            [
                'token' => Helpers::randomStringGenarator(60),
                'created_at' => Carbon::now()
            ]
        );

        $this->sendResetMessage($tokenData, $otp, $isEmailVerification);
    }

    public function sendResetMessage($tokenData, $otp = null, $isEmailVerification = false)
    {
        if($isEmailVerification) {
            Mail::send('emails.forget-password', ['token' => $tokenData->token], function($message){
                $message->to($this->email);
                $message->subject('Reset Password');
            });
        }
    }
}
