<?php

namespace Tests\Feature\Courses;

use App\Enums\CourseRole;
use App\Enums\CourseStatus;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoursePublicationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_authorized_user_can_send_a_course_to_approval(): void
    {
        $user = User::factory()->student()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $user->courses()->attach(
            $course,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/send-for-approval");

        $response->assertOk();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::PendingReview,
        ]);
    }

    public function test_unauthorized_user_cannot_send_a_course_to_approval(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $user->courses()->attach(
            $course,
            [
                'role' => CourseRole::Collaborator,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Draft,
        ]);
    }

    public function test_authorized_admin_can_approve_a_course(): void
    {
        $user = User::factory()->admin()->withPermissions(['approve-course'])->create();

        Sanctum::actingAs($user);

        $owner = User::factory()->professor()->withPermissions(['create-course'])->create();
        $collaborators = User::factory()->student()->count(fake()->numberBetween(1, 5))->create();
        $advisor = User::factory()->professor()->create();

        $course = Course::factory()->pendingReview()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach($owner, [
            'role' => CourseRole::Owner,
        ]);

        $course->users()->attach(
            $collaborators->pluck('user_id'),
            [
                'role' => CourseRole::Collaborator,
            ]
        );

        $course->users()->attach($advisor, [
            'role' => CourseRole::Advisor,
        ]);

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/approve", [
            'commentary' => fake()->paragraphs(3, true),
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Approved,
        ]);
    }

    public function test_unauthorized_admin_cannot_approve_a_course(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->pendingReview()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/approve", [
            'commentary' => fake()->paragraphs(3, true),
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::PendingReview,
        ]);
    }

    public function test_authorized_admin_can_reject_a_course(): void
    {
        $user = User::factory()->admin()->withPermissions(['approve-course'])->create();

        Sanctum::actingAs($user);

        $owner = User::factory()->professor()->withPermissions(['create-course'])->create();
        $collaborators = User::factory()->student()->count(fake()->numberBetween(1, 5))->create();
        $advisor = User::factory()->professor()->create();

        $course = Course::factory()->pendingReview()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach($owner, [
            'role' => CourseRole::Owner,
        ]);

        $course->users()->attach(
            $collaborators->pluck('user_id'),
            [
                'role' => CourseRole::Collaborator,
            ]
        );

        $course->users()->attach($advisor, [
            'role' => CourseRole::Advisor,
        ]);

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/reject", [
            'commentary' => fake()->paragraphs(3, true),
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Draft,
        ]);
    }

    public function test_unauthorized_admin_cannot_reject_a_course(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->pendingReview()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/reject", [
            'commentary' => fake()->paragraphs(3, true),
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::PendingReview,
        ]);
    }

    public function test_authorized_admin_can_publish_a_course(): void
    {
        $user = User::factory()->admin()->withPermissions(['publish-course'])->create();

        Sanctum::actingAs($user);

        $owner = User::factory()->professor()->withPermissions(['create-course'])->create();
        $collaborators = User::factory()->student()->count(fake()->numberBetween(1, 5))->create();
        $advisor = User::factory()->professor()->create();

        $course = Course::factory()->approved()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach($owner, [
            'role' => CourseRole::Owner,
        ]);

        $course->users()->attach(
            $collaborators->pluck('user_id'),
            [
                'role' => CourseRole::Collaborator,
            ]
        );

        $course->users()->attach($advisor, [
            'role' => CourseRole::Advisor,
        ]);

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertOk();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Published,
        ]);
    }

    public function test_authorized_user_can_publish_a_course(): void
    {
        $user = User::factory()->student()->withPermissions(['publish-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->approved()->withLessons()->withCategories()->withSubjects()->create();

        $user->courses()->attach(
            $course,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertOk();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Published,
        ]);
    }

    public function test_unauthorized_user_cannot_publish_a_course(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->approved()->withLessons()->withCategories()->withSubjects()->create();

        $user->courses()->attach(
            $course,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Approved,
        ]);
    }

    public function test_unauthorized_admin_cannot_publish_a_course(): void
    {
        $user = User::factory()->admin()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->approved()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Approved,
        ]);
    }

    public function test_rejects_invalid_transition_to_pending(): void
    {
        $user = User::factory()->student()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->archived()->withLessons()->withCategories()->withSubjects()->create();

        $user->courses()->attach(
            $course,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/send-for-approval");

        $response->assertUnprocessable();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Archived,
        ]);
    }

    public function test_rejects_invalid_transition_to_approved(): void
    {
        $user = User::factory()->admin()->withPermissions(['approve-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/approve");

        $response->assertUnprocessable();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Draft,
        ]);
    }

    public function test_rejects_invalid_transition_to_rejected(): void
    {
        $user = User::factory()->admin()->withPermissions(['approve-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/reject");

        $response->assertUnprocessable();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Draft,
        ]);
    }

    public function test_rejects_invalid_transition_to_publish(): void
    {
        $user = User::factory()->admin()->withPermissions(['publish-course'])->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $response = $this->patchJson("/api/v1/courses/$course->cou_code/publish");

        $response->assertUnprocessable();

        $this->assertDatabaseHas('courses', [
            'cou_code' => $course->cou_code,
            'cou_status' => CourseStatus::Draft,
        ]);
    }
}
