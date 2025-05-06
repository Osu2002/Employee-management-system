<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactHRReplyNotification extends Mailable
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
        return $this->subject('New Reply to HR Inquiry')
                    ->view('emails.hr-reply-notification')
                    ->with('mailDetails', $this->mailDetails);
    }
}
