<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'cou_title' => fake()->sentence(3),
            'cou_short_title' => fake()->words(2, true),
            'cou_description' => mb_substr(fake()->paragraph(), 0, 300),
            'cou_code' => strtoupper(fake()->unique()->bothify('ISC###')),
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
     * Creates between 5 and 20 lessons and assign them to the course.
     * 
     * @return CourseFactory
     */
    public function withLessons(): static
    {
        return $this->afterCreating(function (Course $course) {
            $count = fake()->numberBetween(5, 20);

            Lesson::factory()
                ->count($count)
                ->for($course, 'course')
                ->sequence(
                    fn ($sequence) => [
                        'les_order' => $sequence->index + 1,
                    ]
                )
                ->create();
        });
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

    /**
     * Creates between 1 and 3 subjects and assign them to the course.
     * 
     * @return CourseFactory
     */
    public function withSubjects(): static
    {
        return $this->afterCreating(function (Course $course) {
            $subjects = Subject::factory()->count(fake()->numberBetween(1, 3))->create();

            $course->subjects()->attach($subjects);
        });
    }
}