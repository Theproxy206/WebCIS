<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'cou_title' => fake()->sentence(3),
            'cou_short_title' => fake()->words(2, true),
            'cou_description' => fake()->paragraph(),
            'cou_code' => strtoupper(fake()->bothify('ISC###')),
            'cou_status' => fake()->randomElement(CourseStatus::cases()),
            'cou_path_icon' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'cou_status' => CourseStatus::Draft,
        ]);
    }

    public function pendingReview(): static
    {
        return $this->state(fn () => [
            'cou_status' => CourseStatus::PendingReview,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'cou_status' => CourseStatus::Approved,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'cou_status' => CourseStatus::Published,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'cou_status' => CourseStatus::Archived,
        ]);
    }

    /**
     * Creates between 1 and 4 categories and assign them to the course.
     * 
     * @return CourseFactory
     */
    public function withCategories(): static
    {
        return $this->afterCreating(function (Course $course) {
            $categories = Category::factory()->count(fake()->numberBetween(1, 4))->create();

            $course->categories()->attach($categories);
        });
    }
}