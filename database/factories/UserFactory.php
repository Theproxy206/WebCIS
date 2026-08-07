<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserType;
use App\Enums\EnrollmentStatus;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'user_email' => fake()->unique()->safeEmail(),
            'user_username' => fake()->unique()->userName(),
            'user_control_number' => fake()->numerify('22######'),
            'user_description' => fake()->sentence(),
            'user_path_profile_picture' => null,
            'user_path_banner' => null,
            'user_pass' => Hash::make('password'),
            'user_type' => UserType::Student,
            'user_name' => fake()->firstName(),
            'user_surname' => fake()->lastName(),
            'user_second_surname' => fake()->lastName(),
        ];
    }

    public function professor(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Professor,
        ]);
    }

    public function extern(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Extern,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'user_type' => UserType::Admin,
        ]);
    }

    public function withEnrollments(): static
    {
        return $this->afterCreating(function (User $user) {
            $courses = Course::factory()->count(fake()->numberBetween(1, 5))->published()->create();

            foreach ($courses as $course) {
                $completed = fake()->boolean();

                $joinedAt = fake()->dateTimeBetween('-2 months', '-2 weeks');
                $lastAccess = fake()->dateTimeBetween($joinedAt, 'now');

                $user->courses()->attach(
                    $course,
                    [
                        'status' => $completed ? EnrollmentStatus::Completed : EnrollmentStatus::InProgress,
                        'joined_at' => $joinedAt,
                        'last_accessed_at' => $lastAccess,
                        'completed_at' => $completed ? fake()->dateTimeBetween($joinedAt, $lastAccess) : null,
                    ]
                );

                $lessons = Lesson::factory()
                    ->count(fake()->numberBetween(5, 20))
                    ->for($course, 'course')
                    ->create();

                $completedLessons = $completed ? $lessons->count() : fake()->numberBetween(0, $lessons->count());

                foreach ($lessons as $index => $lesson) {
                    $user->lessons()->attach(
                        $lesson,
                        [
                            'completed' => $index < $completedLessons,
                        ]
                    );
                }
            }
        });
    }
}
