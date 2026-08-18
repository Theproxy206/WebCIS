<?php

namespace Tests\Feature\Courses;

use App\Enums\CourseRole;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_authorized_user_can_delete_course(): void
    {
        $user = User::factory()->student()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach(
            $user,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->deleteJson("/api/v1/courses/$course->cou_code");

        $response->assertNoContent();

        $this->assertDatabaseMissing(
            'courses',
            [
                'cou_code' => $course->cou_code
            ]
        );
    }

    public function test_authorized_admin_can_delete_course(): void
    {
        $user = User::factory()->admin()->withPermissions(['delete-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->published()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->deleteJson("/api/v1/courses/$course->cou_code");

        $response->assertNoContent();

        $this->assertDatabaseMissing(
            'courses',
            [
                'cou_code' => $course->cou_code
            ]
        );
    }

    public function test_unauthorized_user_cannot_delete_course(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach(
            $user,
            [
                'role' => CourseRole::Collaborator,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->deleteJson("/api/v1/courses/$course->cou_code");

        $response->assertForbidden();

        $this->assertDatabaseHas(
            'courses',
            [
                'cou_code' => $course->cou_code
            ]
        );
    }

    public function test_unauthorized_admin_cannot_delete_course(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->deleteJson("/api/v1/courses/$course->cou_code");

        $response->assertForbidden();

        $this->assertDatabaseHas(
            'courses',
            [
                'cou_code' => $course->cou_code
            ]
        );
    }

    public function test_user_cannot_delete_published_course(): void
    {
        $user = User::factory()->student()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->published()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach(
            $user,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->deleteJson("/api/v1/courses/$course->cou_code");

        $response->assertForbidden();

        $this->assertDatabaseHas(
            'courses',
            [
                'cou_code' => $course->cou_code
            ]
        );
    }
}
