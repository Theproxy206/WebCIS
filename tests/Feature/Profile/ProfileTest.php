<?php

namespace Tests\Feature\Dashboard;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_authenticated_user_can_get_data(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('user.username', $user->user_username);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/me')
            ->assertJsonPath('user.username', $user->user_username)
            ->assertJsonPath('user.description', $user->user_description)
            ->assertJsonPath('user.name', $user->user_name)
            ->assertJsonPath('user.surname', $user->user_surname)
            ->assertJsonPath('user.second_surname', $user->user_second_surname);

        $data = [
            'username' => fake()->unique()->userName(),
            'description' => fake()->sentence(),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'second_surname' => fake()->lastName(),
        ];

        $response = $this->patchJson('/api/v1/me', $data);

        $response->assertOk()
            ->assertJsonPath('user.username', $data['username'])
            ->assertJsonPath('user.description', $data['description'])
            ->assertJsonPath('user.name', $data['name'])
            ->assertJsonPath('user.surname', $data['surname'])
            ->assertJsonPath('user.second_surname', $data['second_surname']);

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'user_username' => $data['username'],
            'user_name' => $data['name'],
            'user_surname' => $data['surname'],
            'user_second_surname' => $data['second_surname'],
            'user_description' => $data['description'],
        ]);
    }

    public function test_authenticated_user_can_update_profile_image(): void
    {
        $user = User::factory()->create();

        Storage::fake('public');

        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image(
            'avatar.png',
            512,
            512
        );

        $response = $this->patchJson('/api/v1/me/profile-picture', [
            'image' => $file,
        ]);

        $user->refresh();

        $response->assertOk()
        ->assertJsonPath('user.profile_picture', Storage::disk('public')->url($user->user_path_profile_picture));

        Storage::disk('public')
        ->assertExists(
            User::find($user->user_id)
                ->user_path_profile_picture
        );
    }

    public function test_authenticated_user_can_update_banner(): void
    {
        $user = User::factory()->create();

        Storage::fake('public');

        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image(
            'banner.png',
            1200,
            300
        );

        $response = $this->patchJson('/api/v1/me/banner', [
            'image' => $file,
        ]);

        $user->refresh();

        $response->assertOk()
        ->assertJsonPath('user.banner', Storage::disk('public')->url($user->user_path_banner));

        Storage::disk('public')
        ->assertExists(
            User::find($user->user_id)
                ->user_path_banner
        );
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->withEnrollments()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/profile')
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
        ]);
    }
}