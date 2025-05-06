<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // Extend Authenticatable for authentication
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Employee extends Authenticatable implements HasMedia  // Implement Authenticatable for login functionality
{
    use HasFactory;
    use InteractsWithMedia;

    // Fillable fields to allow mass assignment
    protected $fillable = [
        'name',
        'email',
        'designation',
        'join_date',
        'end_date',
        'leave_types', // Allow leave_types for mass assignment

    ];
    protected $casts = [
        'leave_types' => 'array', // Cast JSON to array automatically
    ];


    // Optionally define relationships for related models
    public function leaves()
    {
        return $this->hasMany(Leaves::class);
    }
    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'designation', 'id'); // Replace 'designation' if the foreign key differs
    }
    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function bank_details()
    {
        return $this->hasOne(BankDetails::class);
    }

    public function service()
    {
        return $this->hasMany(Service::class);
    }
    public function userData()
    {
        return $this->hasOne(UserData::class);
    }
    // You can optionally define the `getAuthIdentifier()` and `getAuthPassword()` if needed,
    // but in most cases extending Authenticatable will take care of them.

    // If your Employee model has a custom column for the password field, you can define this method:
    // public function getAuthPassword() {
    //     return $this->password;  // Or return a custom column like 'employee_password' if needed.
    // }

    // Ensure this field is accessible in the database and it's the field used for authentication
}
