<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApplicationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveDetails;

    /**
     * Create a new message instance.
     *
     * @return void
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
        return $this->subject('Leave Application Submitted Successfully')
                    ->view('emails.leave-application-success');
    }
}
