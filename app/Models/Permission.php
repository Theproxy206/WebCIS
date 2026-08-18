<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $keyType = 'integer';
    protected $primaryKey = 'per_id';
    protected $fillable = [
        'per_code',
        'per_name',
        'per_description'
    ];
    public $timestamps = true;
    public $incrementing = true;

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_permissions', 'fk_permissions', 'fk_users', 'per_id', 'user_id');
    }
}
