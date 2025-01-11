<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use App\Enums\HasPreferencesEnum;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_preferences_set_can_access_feeds(): void {

        $user = User::factory()->create(['has_preferences' => HasPreferencesEnum::YES->value]);

        $response = $this->actingAs($user)->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_with_preferences_not_set_cannot_access_feeds(): void {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
