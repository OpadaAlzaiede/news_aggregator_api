<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\CustomPasswordResetNotification;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link(): void {

        $user = User::factory()->create();

        Notification::fake();

        $this->actingAs($user)->post(route('auth.forgot-password'), [
            'email' => $user->email
        ]);

        Notification::assertSentTo($user, CustomPasswordResetNotification::class);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_user_can_reset_password_with_valid_email_token_and_password(): void {
        $user = User::factory()->create();
        $passwordResetToken = PasswordResetToken::factory()->create(['email' => $user->email]);
        $newPassword = '9RQs67DF@#';

        $response = $this->post(route('auth.reset-password'), [
            'token' => $passwordResetToken->token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $user->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'message'
        ]);
        $this->assertTrue(Hash::check($newPassword, $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_user_cannot_reset_password_with_invalid_token(): void {

        $user = User::factory()->create();
        $passwordResetToken = PasswordResetToken::factory()->create(['email' => $user->email]);
        $newPassword = '9RQs67DF@#';

        $response = $this->post(route('auth.reset-password'), [
            'token' => $passwordResetToken->token.'123',
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $user->refresh();

        $response->assertJsonStructure([
            'errors' => [
                'token'
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse(Hash::check($newPassword, $user->password));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_user_cannot_reset_password_with_expired_token(): void {

        $user = User::factory()->create();
        $passwordResetToken = PasswordResetToken::factory()->create([
            'email' => $user->email,
            'created_at' => now()->subHour()
        ]);
        $newPassword = '9RQs67DF@#';

        $response = $this->post(route('auth.reset-password'), [
            'token' => $passwordResetToken->token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $user->refresh();

        $response->assertJsonStructure([
            'errors' => [
                'token'
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse(Hash::check($newPassword, $user->password));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_user_cannot_reset_password_with_invalid_email(): void {

        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $passwordResetToken = PasswordResetToken::factory()->create(['email' => $firstUser->email]);
        $newPassword = '9RQs67DF@#';

        $response = $this->post(route('auth.reset-password'), [
            'token' => $passwordResetToken->token,
            'email' => $secondUser->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $firstUser->refresh();

        $response->assertJsonStructure([
            'errors' => [
                'email'
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse(Hash::check($newPassword, $firstUser->password));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $firstUser->email]);
    }

    public function test_user_cannot_reset_password_with_invalid_password(): void {

        $user = User::factory()->create();
        $passwordResetToken = PasswordResetToken::factory()->create(['email' => $user->email]);
        $newPassword = 'pass';

        $response = $this->post(route('auth.reset-password'), [
            'token' => $passwordResetToken->token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $user->refresh();

        $response->assertJsonStructure([
            'errors' => [
                'password'
            ]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertFalse(Hash::check($newPassword, $user->password));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }
}
