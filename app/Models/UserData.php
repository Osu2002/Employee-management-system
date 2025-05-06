<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserData extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    /**
     * Specify the table name.
     */
    protected $table = 'user_data';

    /**
     * Fillable fields for mass assignment.
     */
    protected $fillable = [
        'employee_id',
        'cover_photo',
        'project_name',
        'project_description',
        'skills',
        'contributors',
        'media',
    ];

    /**
     * Cast attributes to appropriate data types.
     */
    protected $casts = [
        'skills' => 'array',
        'contributors' => 'array',
        'media' => 'array',
    ];

    /**
     * Relationship to the Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
