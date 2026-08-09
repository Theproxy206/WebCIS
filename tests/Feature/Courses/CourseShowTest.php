<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseShowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    
    public function test_authenticated_user_can_get_specific_course(): void
    {
        Course::factory()->count(fake()->numberBetween(10, 20))->withCategories()->withSubjects()->create();

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $target = Course::factory()->withLessons()->withCategories()->withSubjects()->create([
            'cou_code' => 'TARGET',
        ]);

        $request = $this->getJson("/api/v1/courses/$target->cou_code");

        $request->assertOk()
        ->assertJsonPath(
            'data.code',
            $target->cou_code
        );
    }

    public function test_rejects_unauthenticated_user(): void
    {
        $course = Course::factory()->create();

        $request = $this->getJson("/api/v1/courses/$course->cou_code");

        $request->assertUnauthorized();
    }
}
