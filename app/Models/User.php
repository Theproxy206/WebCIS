<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_email',
        'user_username',
        'user_control_number',
        'user_description',
        'user_path_profile_picture',
        'user_path_banner',
        'user_pass',
        'user_type',
        'user_name',
        'user_surname',
        'user_second_surname'
    ];
    public $timestamps = true;
}


