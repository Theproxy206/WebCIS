<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
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

    public function services(): HasMany
    {
        return $this->hasMany(CourseService::class, 'fk_course_services_courses', 'cou_token');
    }
}
