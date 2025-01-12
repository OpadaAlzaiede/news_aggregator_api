<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifiedEmailMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_unauthorized_when_email_is_not_verified(): void {

        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_passes_the_request_when_email_is_verified(): void {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
