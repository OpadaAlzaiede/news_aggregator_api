<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use App\Jobs\SyncUserFeedJob;
use App\Enums\PreferenceForEnum;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_set_preferences(): void {

        $user = User::factory()->create();
        $preferences = $this->createPreferences(5);
        Queue::fake();

        $response = $this->actingAs($user)->post(route('preferences.store'), ['preferences' => $preferences]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertTrue($user->hasPreferences());
        $this->assertCount(5, $user->preferences);
        Queue::assertPushed(SyncUserFeedJob::class);

    }

    public function test_user_cannot_set_more_than_ten_preferences(): void {

        $user = User::factory()->create();
        $preferences = $this->createPreferences(11);
        Queue::fake();

        $response = $this->actingAs($user)->post(route('preferences.store'), ['preferences' => $preferences]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse($user->hasPreferences());
        $this->assertCount(0, $user->preferences);
        Queue::assertNotPushed(SyncUserFeedJob::class);
    }

    public function test_user_cannot_set_duplicate_preferences(): void {

        $user = User::factory()->create();
        $preferences = $this->createPreferences(5, true);
        Queue::fake();

        $response = $this->actingAs($user)->post(route('preferences.store'), ['preferences' => $preferences]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse($user->hasPreferences());
        $this->assertCount(0, $user->preferences);
        Queue::assertNotPushed(SyncUserFeedJob::class);
    }


    public function test_user_can_access_his_preferences(): void {

        $user = User::factory()->create();
        $preferences = $this->createPreferences(5);

        $this->actingAs($user)->post(route('preferences.store'), ['preferences' => $preferences]);

        $response = $this->actingAs($user)->get(route('preferences.index'));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_user_can_destroy_his_preferences(): void {

        $user = User::factory()->create();
        $preferences = $this->createPreferences(5);

        $this->actingAs($user)->post(route('preferences.store'), ['preferences' => $preferences]);

        $response = $this->actingAs($user)->delete(route('preferences.destroy'));

        $response->assertStatus(Response::HTTP_OK);
        $this->assertFalse($user->hasPreferences());
        $this->assertCount(0, $user->preferences);
    }

    /**
     * Create random preferences array
     *
     * @param int $count
     * @param bool $withDuplicates
     */
    private function createPreferences(int $count = 5, bool $withDuplicates = false) {

        $preferences = [];

        for($i = 0; $i < $count; $i++) {

            $preferences[] = [
                'preference_type' => collect(PreferenceForEnum::cases())->shuffle()->first()?->value,
                'preference_value' => fake()->unique()->text(10)
            ];
        }

        if($withDuplicates) {

            $preferences[] = $preferences[0];
        }

        return $preferences;
    }
}
