<?php

namespace Tests\Feature;

use Tests\MyBaseTestCase;

class AuthControllerTest extends MyBaseTestCase
{
    public function test_register(): void
    {
        $uri = '/api/register';
        $body = [
            'name' => 'John5 Doe',
            'email' => 'jo78hhhfn@doe.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson($uri, $body, $this->getHeader());

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => 'John5 Doe',
                'email' => 'jo78hhhfn@doe.com',
            ],
        ]);
    }

    public function test_login()
    {
        $user = $this->user;
        $uri = '/api/login';
        $body = [
            'email' => $user->getEmailForVerification(),
            'password' => 'password',
        ];

        $response = $this->postJson($uri, $body);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_logout()
    {
        $response = $this->postJson('/api/logout', [], $this->getHeader());

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => null,
            'message' => 'User logged out successfully.',
        ]);
    }
}
