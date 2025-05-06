<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';

    protected $fillable = ['send_to','type', 'token', 'resettable_type', 'resettable_id', 'created_at'];
    public $timestamps = false;

    public function resettable()
    {
        return $this->morphTo();
    }
}
