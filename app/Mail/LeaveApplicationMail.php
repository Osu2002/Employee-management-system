<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveDetails;

    /**
     * Create a new message instance.
     *
     * @param array $leaveDetails
     */
    public function __construct($leaveDetails)
    {
        $this->leaveDetails = $leaveDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Leave Application Submitted')
                    ->view('emails.leave_application') 
                    ->with('leaveDetails', $this->leaveDetails);
    }
}
