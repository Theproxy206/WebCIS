<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    
    protected $table = 'courses';
    protected $primaryKey = 'cou_token';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cou_token',
        'cou_title',
        'cou_short_title',
        'cou_description',
        'cou_code',
        'cou_content',
        'cou_path_icon',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'fk_lessons_courses', 'cou_token');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'courses_users', 'fk_courses', 'fk_users', 'cou_token', 'user_id')->withPivot(['role', 'status', 'joined_at', 'completed_at', 'last_accessed_at']);
    }
}
