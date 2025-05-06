<?php
namespace App\Mail;

use App\Models\ContactHRMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactHRNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $messageDetails;

    /**
     * Create a new message instance.
     *
     * @param HRMessage $message
     */
    public function __construct(ContactHRMessage $message)
    {
        $this->messageDetails = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New HR Inquiry from ' . $this->messageDetails->name)
            ->view('emails.contact_hr_notification')
            ->with([
                'name' => $this->messageDetails->name,
                'email' => $this->messageDetails->email,
                'subject' => $this->messageDetails->subject,
                'messageContent' => $this->messageDetails->message,
            ]);
    }
}
