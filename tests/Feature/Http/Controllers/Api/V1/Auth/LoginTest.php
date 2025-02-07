<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {

        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_with_unverified_email_cannot_login(): void
    {

        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {

        $response = $this->post(route('auth.login'), [
            'email' => fake()->email(),
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_logged_user_can_logout(): void
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
