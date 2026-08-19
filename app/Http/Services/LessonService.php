<?php

namespace App\Http\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class LessonService {
    /**
     * Returns the lessons of the given course
     * 
     * @param Course $course
     * @return Collection<int, mixed>
     */
    public function showLessons(Course $course): Collection
    {
        return $course->lessons()->orderBy('les_order')->get();
    }
}