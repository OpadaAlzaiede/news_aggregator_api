<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Tests\Feature\Api\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void {

        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void {

        $response = $this->post(route('auth.login'), [
            'email' => fake()->email(),
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_logged_user_can_logout(): void {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
