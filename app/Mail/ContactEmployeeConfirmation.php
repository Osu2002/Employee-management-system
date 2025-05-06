<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmployeeConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $messageDetails;

    /**
     * Create a new message instance.
     *
     * @param $messageDetails
     */
    public function __construct($messageDetails)
    {
        $this->messageDetails = $messageDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmation: Your Message to HR')
            ->view('emails.contact_employee_confirmation');
    }
}
