<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee, $otp)
    {
        $this->employee = $employee;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.otp')
        ->subject('EMS OTP Verification Code')
                    ->with([
                        'employee_name' => $this->employee->name,
                        'otp' => $this->otp,
                    ]);
    }
}
