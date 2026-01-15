<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users_temp extends Model
{
    protected $table = "users_temp";

    protected $primaryKey = null;
    public $incrementing = false;


    protected $fillable = [
        'users_email',
        'token',
        'user_type',
        'request_type',
    ];

    /**Timestamps*/
    public $timestamps = true;
}
