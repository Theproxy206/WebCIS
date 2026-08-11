<?php

namespace Tests\Feature\Courses;

use App\Enums\CourseRole;
use App\Enums\CourseStatus;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * A basic feature test example.
     */
    public function test_authorized_user_can_update_course(): void
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

        Storage::fake('public');

        $file = UploadedFile::fake()->image(
            'icon.png',
            512,
            512
        );

        $response = $this->postJson('/api/v1/courses/icon', [
            'image' => $file,
        ]);

        $path = $response->json('path');

        $categories = Category::factory()->count(fake()->numberBetween(1, 5))->create();
        $catCodes = $categories->pluck('cat_code')->toArray();
        $subjects = Subject::factory()->count(fake()->numberBetween(1, 5))->create();
        $subCodes = $subjects->pluck('sub_code')->toArray();

        $data = [
            'code' => strtoupper(fake()->unique()->bothify('ISC###')),
            'title' => fake()->sentence(3),
            'short_title' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'icon' => $path,
            'categories' => $catCodes,
            'subjects' => $subCodes,
        ];

        $response = $this->patchJson("/api/v1/courses/$course->cou_code", $data);

        $response->assertOk()
        ->assertJsonStructure([
            'message',
            'course' => [
                'code',
                'title',
                'short_title',
                'description',
                'categories' => [
                    '*' => [
                        'code',
                        'name'
                    ]
                ],
                'subjects' => [
                    '*' => [
                        'code',
                        'name'
                    ]
                ],
                'status',
                'icon'
            ],
        ]);

        $course = Course::where('cou_code', $data['code'])->firstOrFail();

        $this->assertDatabaseHas(
            'courses',
            [
                'cou_id' => $course->cou_id,
                'cou_code' => $data['code'],
                'cou_title' => $data['title'],
                'cou_short_title' => $data['short_title'],
                'cou_description' => $data['description'],
                'cou_status' => CourseStatus::Draft,
                'cou_path_icon' => $data['icon'],
            ]
        );

        $catSerials = $categories->pluck('cat_serial')->toArray();
        $subSerials = $subjects->pluck('sub_serial')->toArray();

        $this->assertDatabaseHas(
            'categories_courses',
            [
                'fk_courses' => $course->cou_id,
                'fk_categories' => $catSerials
            ]
        );

        $this->assertDatabaseHas(
            'subjects_courses',
            [
                'fk_courses' => $course->cou_id,
                'fk_subjects' => $subSerials
            ]
        );
    }

    public function test_collaborator_cannot_update_course(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $owner = User::factory()->professor()->withPermissions(['create-course'])->create();
        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach(
            $owner,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $course->users()->attach(
            $user,
            [
                'role' => CourseRole::Collaborator,
                'created_at' => Carbon::now(),
            ]
        );

        $categories = Category::factory()->count(fake()->numberBetween(1, 5))->create();
        $catCodes = $categories->pluck('cat_code')->toArray();
        $subjects = Subject::factory()->count(fake()->numberBetween(1, 5))->create();
        $subCodes = $subjects->pluck('sub_code')->toArray();

        $data = [
            'code' => strtoupper(fake()->unique()->bothify('ISC###')),
            'title' => fake()->sentence(3),
            'short_title' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'categories' => $catCodes,
            'subjects' => $subCodes,
        ];

        $response = $this->patchJson("/api/v1/courses/$course->cou_code", $data);

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_update_course(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $owner = User::factory()->professor()->withPermissions(['create-course'])->create();
        $course = Course::factory()->draft()->withLessons()->withCategories()->withSubjects()->create();

        $course->users()->attach(
            $owner,
            [
                'role' => CourseRole::Owner,
                'created_at' => Carbon::now(),
            ]
        );

        $categories = Category::factory()->count(fake()->numberBetween(1, 5))->create();
        $catCodes = $categories->pluck('cat_code')->toArray();
        $subjects = Subject::factory()->count(fake()->numberBetween(1, 5))->create();
        $subCodes = $subjects->pluck('sub_code')->toArray();

        $data = [
            'code' => strtoupper(fake()->unique()->bothify('ISC###')),
            'title' => fake()->sentence(3),
            'short_title' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'categories' => $catCodes,
            'subjects' => $subCodes,
        ];

        $response = $this->patchJson("/api/v1/courses/$course->cou_code", $data);

        $response->assertForbidden();
    }
}
