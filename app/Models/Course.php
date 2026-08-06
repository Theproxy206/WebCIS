<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    
    protected $table = 'courses';
    protected $primaryKey = 'cou_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cou_id',
        'cou_title',
        'cou_short_title',
        'cou_description',
        'cou_code',
        'cou_path_icon',
    ];

    protected static function booted(): void
    {
        static::creating(function ($course) {
            if (empty($course->{$course->getKeyName()})) {
                $course->{$course->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'fk_lessons_courses', 'cou_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'courses_users', 'fk_courses', 'fk_users', 'cou_id', 'user_id')->withPivot(['role', 'status', 'joined_at', 'completed_at', 'last_accessed_at']);
    }
}
