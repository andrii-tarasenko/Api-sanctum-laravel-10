<?php

namespace Tests;

use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MyBaseTestCase extends TestCase
{
    use RefreshDatabase;

    public User $user;

    const TOKEN_FIRST_ELEMENT_IN_ARRAY = 1;

    public function setUp(): void
    {
        parent::setUp();

        $input = [
            'name' => 'Test User',
            'email' => 'jo7hhhfn@doe.com',
            'password' => Hash::make('password'),
        ];

        $user = new User();
        $user->fill($input);
        $user->save();

        $this->user = $user;
    }

    /**
     * Create Token for test authorisation.
     *
     * @return string
     */
    public function getToken(): string
    {
        $user = $this->user;
        $token = explode('|', $user->createToken('MyApp')->plainTextToken);

        return $token[self::TOKEN_FIRST_ELEMENT_IN_ARRAY];
    }

    public function getHeader(): array
    {
        $token = $this->getToken();

        return ['Authorization' => 'Bearer ' . $token];
    }

    public function createTaskForExample(): void
    {
        $task = new Task();
        $task->title = 'Test Title';
        $task->description = 'Test description';
        $task->user_id = $this->user->id;
        $task->save();
    }
}
