<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $table = 'lessons';
    protected $primaryKey = 'les_serial';

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
}
