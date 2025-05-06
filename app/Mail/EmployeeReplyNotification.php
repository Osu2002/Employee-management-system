<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeReplyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mailDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($mailDetails)
    {
        $this->mailDetails = $mailDetails;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Reply from ' . $this->mailDetails['employeeName'])
                    ->view('emails.employeeReplyNotification')
                    ->with('mailDetails', $this->mailDetails);
    }
}
