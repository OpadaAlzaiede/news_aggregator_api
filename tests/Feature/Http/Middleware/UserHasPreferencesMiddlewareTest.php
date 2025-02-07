<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;

class UserHasPreferencesMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_unauthorized_when_user_does_not_have_preferences(): void
    {

        $user = User::factory()->create(['has_preferences' => false]);

        $response = $this->actingAs($user)->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_passes_the_request_when_user_has_preferences(): void
    {

        $user = User::factory()->create(['has_preferences' => true]);

        $response = $this->actingAs($user)->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_OK);
    }
}
