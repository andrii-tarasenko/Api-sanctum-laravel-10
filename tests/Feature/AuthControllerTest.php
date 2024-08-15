<?php

namespace Tests\Feature;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    final public const TOKEN_FIRST_ELEMENT_IN_ARRAY = 1;

    public function test_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John5 Doe',
            'email' => 'jo7hhhfn@doe.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => 'John5 Doe',
                'email' => 'jo7hhhfn@doe.com',
                'id' => 1,
            ],
        ]);
    }

    public function test_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'jo7hhhfn@doe.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_logout()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'jo7hhhfn@doe.com',
            'password' => bcrypt('password'),
        ]);

        $token = explode('|', $user->createToken('MyApp')->plainTextToken);
        $token = $token[self::TOKEN_FIRST_ELEMENT_IN_ARRAY];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => null,
            'message' => 'User logged out successfully.',
        ]);
    }
}
