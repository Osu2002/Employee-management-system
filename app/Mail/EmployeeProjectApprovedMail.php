<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeProjectApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeName;
    public $projectName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employeeName, $projectName)
    {
        $this->employeeName = $employeeName;
        $this->projectName = $projectName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Project Has Been Approved')
                    ->view('emails.employee_project_approved');
    }
}
