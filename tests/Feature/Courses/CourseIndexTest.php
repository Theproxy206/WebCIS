<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseIndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_authenticated_user_can_get_courses(): void
    {
        $user = User::factory()->create();
        Course::factory()->count(fake()->numberBetween(10, 20))->published()->withCategories()->withSubjects()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/courses');

        $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'code',
                    'title',
                ],
            ],
            'links',
            'meta',
        ]);
    }

    public function test_authenticated_user_can_change_number_of_courses_per_page(): void
    {
        $user = User::factory()->create();
        Course::factory()->count(fake()->numberBetween(10, 20))->published()->withCategories()->withSubjects()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?per_page=5'
        );

        $response
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_authenticated_user_can_search_courses(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?search=laravel'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath(
                'data.0.title',
                'Laravel desde Cero: El framework moderno de PHP'
            );
    }

    public function test_authenticated_user_can_search_courses_by_code(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?search=LARAVEL-101'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_authenticated_user_can_filter_courses_by_category(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $backend = Category::factory()->create([
            'cat_code' => 'backend',
        ]);

        $frontend = Category::factory()->create([
            'cat_code' => 'frontend',
        ]);

        $backendCourse = Course::factory()->published()->create();
        $frontendCourse = Course::factory()->published()->create();

        $backendCourse->categories()->attach($backend);
        $frontendCourse->categories()->attach($frontend);

        $response = $this->getJson(
            '/api/v1/courses?categories[]=backend'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath(
                'data.0.code',
                $backendCourse->cou_code
            );
    }

    public function test_authenticated_user_can_filter_courses_by_subject(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $php = Subject::factory()->create([
            'sub_code' => '1Z1',
        ]);

        $javascript = Subject::factory()->create([
            'sub_code' => '1Z2',
        ]);

        $phpCourse = Course::factory()->published()->create();
        $jsCourse = Course::factory()->published()->create();

        $phpCourse->subjects()->attach($php);
        $jsCourse->subjects()->attach($javascript);

        $response = $this->getJson(
            '/api/v1/courses?subjects[]=1Z1'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_rejects_invalid_per_page(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?per_page=1000'
        );

        $response->assertUnprocessable();
    }

    public function test_rejects_nonexistent_category(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?categories[]=does-not-exist'
        );

        $response->assertUnprocessable();
    }

    public function test_rejects_invalid_order(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/courses?order=random'
        );

        $response->assertUnprocessable();
    }
}
