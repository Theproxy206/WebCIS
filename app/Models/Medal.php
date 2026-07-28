<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medal extends Model
{
    protected $table = 'medals';
    protected $primaryKey = 'med_serial';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'med_name',
        'med_description',
        'med_path_image',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_medals', 'fk_medals', 'fk_users', 'med_serial', 'user_id')->withPivot('obtained_at');
    }
}
