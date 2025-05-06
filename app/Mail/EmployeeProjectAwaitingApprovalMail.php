<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeProjectAwaitingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeName;
    public $projectName;

    public function __construct($employeeName, $projectName)
    {
        $this->employeeName = $employeeName;
        $this->projectName = $projectName;
    }

    public function build()
    {
        return $this->subject('Your Project is Awaiting Approval')
                    ->view('emails.employee_project_awaiting');
    }
}
