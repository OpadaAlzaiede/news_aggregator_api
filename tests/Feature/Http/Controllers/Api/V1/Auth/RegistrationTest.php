<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Http\Controllers\Api\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {

        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => '9RQs67DF@#',
            'password_confirmation' => '9RQs67DF@#',
        ];

        $response = $this->post(route('auth.register'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }

    #[DataProvider('invalidRegistrationData')]
    public function test_user_cannot_register_with_invalid_data($name, $email, $password, $passwordConfirmation): void
    {

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ];

        $response = $this->post(route('auth.register'), $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('users', ['email' => $data['email']]);
    }

    public static function invalidRegistrationData(): array
    {

        return [
            'invalid email' => ['john doe', 'invalid_email', '9RQs67DF@#', '9RQs67DF@#'],
            'invalid password' => ['john doe', 'john@gmail.com', 'password', 'password'],
            'password mismatch' => ['john doe', 'john@gmail.com', '9RQs67DF@#', '9RQs67D'],
        ];
    }
}
