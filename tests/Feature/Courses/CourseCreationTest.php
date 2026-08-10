<?php

namespace Tests\Feature\Courses;

use App\Enums\CourseStatus;
use App\Models\Category;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseCreationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_authorized_user_can_upload_course_icon(): void
    {
        $user = User::factory()->professor()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);
        Storage::fake('public');

        $file = UploadedFile::fake()->image(
            'icon.png',
            512,
            512
        );

        $response = $this->postJson('/api/v1/courses/icon', [
            'image' => $file,
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'path',
                'url',
            ]);

        $path = $response->json('path');

        Storage::disk('public')->assertExists($path);
    }

    public function test_upload_icon_rejects_unauthorized_user(): void
    {
        $user = User::factory()->professor()->create();

        Sanctum::actingAs($user);
        Storage::fake('public');

        $file = UploadedFile::fake()->image(
            'icon.png',
            512,
            512
        );

        $response = $this->postJson('/api/v1/courses/icon', [
            'image' => $file,
        ]);

        $response->assertForbidden();
    }
    
    public function test_authorized_user_can_create_course(): void
    {
        $user = User::factory()->professor()->withPermissions(['create-course'])->create();

        Sanctum::actingAs($user);

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

        $response = $this->postJson('/api/v1/courses', $data);

        $response->assertCreated()
        ->assertJsonStructure([
            'course' => [
                'code',
                'title',
                'short_title',
                'description',
                'icon'
            ],
        ]);

        $this->assertDatabaseHas(
            'courses',
            [
                'cou_code' => $data['code'],
                'cou_title' => $data['title'],
                'cou_short_title' => $data['short_title'],
                'cou_description' => $data['description'],
                'cou_status' => CourseStatus::Draft,
                'cou_path_icon' => $data['icon'],
            ]
        );
    }

    public function test_create_course_rejects_unauthorized_user(): void
    {
        $user = User::factory()->professor()->create();

        Sanctum::actingAs($user);

        $categories = Category::factory()->count(fake()->numberBetween(1, 5))->create();
        $catCodes = $categories->pluck('cat_code')->toArray();
        $subjects = Subject::factory()->count(fake()->numberBetween(1, 5))->create();
        $subCodes = $subjects->pluck('sub_code')->toArray();

        $data = [
            'code' => strtoupper(fake()->unique()->bothify('ISC###')),
            'title' => fake()->sentence(3),
            'short_title' => fake()->words(2, true),
            'categories' => $catCodes,
            'subjects' => $subCodes,
        ];

        $response = $this->postJson('/api/v1/courses', $data);

        $response->assertForbidden();
    }
}
