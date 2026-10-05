<?php

namespace Tests\Feature\Lessons;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LessonIndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_authorized_user_can_get_lessons(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->withLessons()->create();

        $response = $this->getJson("/api/v1/courses/$course->cou_code/lessons");

        $response->assertOk()
        ->assertJsonStructure([
            'lessons' => [
                '*' => [
                    'id',
                    'title',
                    'short_title',
                    'order'
                ]
            ]
        ]);
    }

    public function test_authorized_user_can_get_one_lesson(): void
    {
        $user = User::factory()->student()->create();

        Sanctum::actingAs($user);

        $course = Course::factory()->withLessons()->create();

        $lesson = $course->lessons()->first();

        $response = $this->getJson("/api/v1/courses/$course->cou_code/lessons/$lesson->les_serial");

        dd($response->getContent());

        $response->assertOk()
            ->assertJsonPath('lesson.id', $lesson->les_serial)
            ->assertJsonPath('lesson.title', $lesson->les_title)
            ->assertJsonPath('lesson.short_title', $lesson->les_short_title);
    }
}
