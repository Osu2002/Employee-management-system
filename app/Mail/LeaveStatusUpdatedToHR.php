<?php
namespace App\Mail;

use App\Models\EmpLeave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveStatusUpdatedToHR extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    /**
     * Create a new message instance.
     *
     * @param EmpLeave $leave
     */
    public function __construct(EmpLeave $leave)
    {
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Leave Status Updated for Employee: ' . $this->leave->employee->name)
            ->view('emails.leave_status_updated_to_hr')
            ->with([
                'employeeName' => $this->leave->employee->name,
                'leaveType' => $this->leave->leave_type,
                'fromDate' => $this->leave->from_date,
                'toDate' => $this->leave->to_date,
                'status' => $this->leave->status,
                'instructions' => $this->leave->instructions,
            ]);
    }
}
