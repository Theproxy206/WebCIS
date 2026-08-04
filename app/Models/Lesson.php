<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;
    
    protected $table = 'lessons';
    protected $primaryKey = 'les_serial';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'les_title',
        'les_short_title',
    ];


    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'fk_lessons_courses', 'cou_token');
    }

    public function parentLesson(): BelongsTo {
        return $this->belongsTo(Lesson::class, 'fk_lessons_lessons', 'les_serial');
    }

    public function subLessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'fk_lessons_lessons', 'les_serial');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_lessons', 'fk_lessons', 'fk_users', 'les_serial', 'user_id')
        ->withPivot('completed');
    }
}
