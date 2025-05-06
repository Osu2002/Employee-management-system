<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpLeave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'leave_type',
        'from_date',
        'to_date',
        'partial_days',
        'duration',
        'start_time', // Added for specifying start time
        'end_time', // Added for specifying end time
        'comments',
        'proof',
        'status',
        'instructions',

    ];

    /**
     * The attributes that have default values.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'Pending', // Set the default value for status
    ];

    /**
     * Define the relationship to the Employee model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Mutator for setting start_time and end_time in a proper format.
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = date('H:i:s', strtotime($value));
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = date('H:i:s', strtotime($value));
    }

    /**
     * Accessor to retrieve human-readable leave duration.
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->duration === 'Full Day') {
            return '1 Full Day';
        } elseif ($this->duration === 'Half Day') {
            return '0.5 Day';
        } elseif ($this->duration === 'Specify Time') {
            return $this->start_time . ' to ' . $this->end_time;
        }
        return $this->duration;
    }
}
