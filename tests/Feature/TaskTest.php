<?php

namespace Tests\Feature;

use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\MyBaseTestCase;

class TaskTest extends MyBaseTestCase
{
    public function test_create_task()
    {
        $uri = '/api/tasks';
        $body = [
            'title' => 'Task 1',
            'description' => 'description 1',
        ];

        $response = $this->postJson($uri, $body, $this->getHeader());
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'title' => 'Task 1',
                'description' => 'description 1',
                'user_id' => 1,
            ],
            'message' => 'Task was created',
        ]);
    }

    public function test_get_all_tasks()
    {
        $uri = '/api/tasks';

        $this->createTaskForExample();

        $response = $this->getJson($uri, $this->getHeader());

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'All tasks was send.',
        ]);
    }

    public function test_show_task()
    {
        $token = $this->getToken();
        $uri = '/api/tasks/1';
        $header = ['Authorization' => 'Bearer ' . $token];

        $this->createTaskForExample();

        $response = $this->getJson($uri, $header);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task was retrieved',
        ]);
    }

    public function test_update_task()
    {
        $uri = '/api/tasks/1';

        $this->createTaskForExample();

        $input = [
            'title' => 'Task update',
            'description' => 'description update',
            'status' => 'completed',
        ];

        $response = $this->putJson($uri, $input, $this->getHeader());
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task was updated',
        ]);
    }

    public function test_delete_task()
    {
        $this->createTaskForExample();

        $uri = '/api/tasks/1';

        $response = $this->deleteJson($uri, [], $this->getHeader());

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Task was deleted',
        ]);
    }
}
