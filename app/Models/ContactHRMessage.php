<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactHRMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'reply', // Admin replies (JSON array)
        'emp_replies', // Employee replies (JSON array)
        'replied_at', // Timestamp for the last reply
    ];

    protected $casts = [
        'replies' => 'array', // Cast reply to an array
        'emp_replies' => 'array', // Cast emp_replies to an array
    ];

    /**
     * Add a new admin reply to the reply JSON column.
     *
     * @param string $reply
     */
    public function addAdminReply(string $reply)
    {
        $currentReplies = $this->reply ?? [];
        $currentReplies[] = [
            'from' => 'admin',
            'reply' => $reply,
            'timestamp' => now()->toISOString(),
        ];
        $this->reply = $currentReplies;
        $this->replied_at = now();
        $this->save();
    }

    /**
     * Add a new employee reply to the emp_replies JSON column.
     *
     * @param string $reply
     */
    public function addEmployeeReply(string $reply)
    {
        $currentEmpReplies = $this->emp_replies ?? [];
        $currentEmpReplies[] = [
            'from' => 'employee',
            'reply' => $reply,
            'timestamp' => now()->toISOString(),
        ];
        $this->emp_replies = $currentEmpReplies;
        $this->save();
    }

    /**
     * Retrieve the combined chat history from both admin and employee replies.
     *
     * @return array
     */
    public function getChatHistory(): array
    {
        $adminReplies = $this->reply ?? [];
        $employeeReplies = $this->emp_replies ?? [];

        $allReplies = array_merge($adminReplies, $employeeReplies);

        // Sort by timestamp
        usort($allReplies, function ($a, $b) {
            return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
        });

        return $allReplies;
    }
}
