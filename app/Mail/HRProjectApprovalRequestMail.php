<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HRProjectApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeName;
    public $projectDetails;

    public function __construct($employeeName, $projectDetails)
    {
        $this->employeeName = $employeeName;
        $this->projectDetails = $projectDetails;
    }

    public function build()
    {
        return $this->subject('Project Submission Pending Approval')
                    ->view('emails.hr_project_approval');
    }
}
