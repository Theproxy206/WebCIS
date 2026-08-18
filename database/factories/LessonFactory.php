<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'les_title' => fake()->sentence(4),
            'les_short_title' => fake()->words(2, true),
            'les_order' => fake()->numberBetween(0, 9),
            'fk_lessons_courses' => Course::factory(),
            'fk_lessons_lessons' => null,
        ];
    }
}