<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::where(
            'user_username',
            'student'
        )->firstOrFail();

        Sanctum::actingAs($user);

        $response = $this
            ->getJson('/api/v1/dashboard');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'user' => [
                    'username',
                    'email',
                    'control_number',
                    'name',
                    'surname',
                    'second_surname',
                    'description',
                    'type',
                    'profile_picture',
                    'banner',
                    'created_at',
                ],
                'medals',
                'courses',
                'progress',
                'recent_course_progress',
                'recent_courses',
            ])
            ->assertJsonPath('user.username', 'student')
            ->assertJsonPath('medals', 1)
            ->assertJsonCount(2, 'recent_courses');
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this
            ->getJson('/api/v1/dashboard')
            ->assertUnauthorized();
    }
}