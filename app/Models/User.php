<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

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
    protected $hidden = [
        'user_pass'
    ];
    public $timestamps = true;

    protected function casts(): array
    {
        return [
            'user_type' => UserType::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getAuthPassword(): string
    {
        return $this->user_pass;
    }

    public function rules() : HasMany
    {
        return $this->hasMany(Rule::class, 'fk_users', 'user_id')
        ->with('granted');
    }

    public function medals() : BelongsToMany
    {
        return $this->belongsToMany(Medal::class, 'users_medals', 'fk_users', 'fk_medals', 'user_id', 'med_serial')
        ->withPivot('obtained_at');
    }

    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'courses_users', 'fk_users', 'fk_courses', 'user_id', 'cou_id')
        ->as('enrollment')
        ->withPivot(['role', 'status', 'joined_at', 'completed_at', 'last_accessed_at']);
    }

    public function lessons() : BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'users_lessons', 'fk_users', 'fk_lessons', 'user_id', 'les_serial')
        ->withPivot('completed');
    }
}
