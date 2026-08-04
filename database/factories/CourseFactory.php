<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'cou_token' => Str::random(12),
            'cou_title' => fake()->sentence(3),
            'cou_short_title' => fake()->words(2, true),
            'cou_description' => fake()->paragraph(),
            'cou_code' => strtoupper(fake()->bothify('ISC###')),
            'cou_content' => 'placeholder',
            'cou_path_icon' => null,
        ];
    }
}