<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{
    protected $fillable = ['name', 'description', 'allowed_leaves', 'leave_types','status'];
    protected $table = 'job_roles'; 
    protected $casts = [
        'leave_types' => 'array', // Cast leave_types as an array
    ];
}
