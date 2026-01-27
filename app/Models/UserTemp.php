<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTemp extends Model
{
    protected $table = 'users_temp';
    protected $primaryKey = 'user_email';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_email',
        'token',
        'expires_at',
        'request_type'
    ];

    protected $hidden = [
        'token'
    ];
}
