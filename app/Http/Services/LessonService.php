<?php

namespace App\Http\Services;

use App\Models\Course;
use App\Models\Lesson;
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

    /**
     * Returns one specific lesson of the given course
     * 
     * @param Course $course
     * @param int $serial id of the lesson
     * @return Lesson
     */
    public function lesson(Course $course, int $serial): Lesson
    {
        return $course->lessons()->where('les_serial', $serial)->firstOrFail();
    }
}