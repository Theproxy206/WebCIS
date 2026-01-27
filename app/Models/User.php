<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

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
    protected $casts = [
        'user_type' => UserType::class,
    ];
    public $timestamps = true;

    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    public function rules() : HasMany
    {
        return $this->hasMany(Rule::class, 'fk_users', 'user_id')->with('granted');
    }
}
