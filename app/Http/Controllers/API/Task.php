<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Domain\Task\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Task extends BaseController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $allTasks = $this->taskService->getAllTasks();

        if ($allTasks === null) {
            return $this->sendError(422, 'Comment is not found');
        }

        return $this->sendResponse($allTasks, 'All tasks was send.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, $validator->errors());
        }

        $input = [
            'title' => $request['title'],
            'description' => $request['description'],
        ];
        $newTask = $this->taskService->createTask($input);

        if ($newTask === null) {
            return $this->sendError(422, 'Task was not created');
        }

        return $this->sendResponse($newTask, 'Task was created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if ($task === null) {
            return $this->sendError(422, 'Task was not found');
        }

        return $this->sendResponse($task, 'Task was retrieved');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, $validator->errors());
        }

        if ($this->taskService->updateTaskById($request->all(), $id)) {
            return $this->sendResponse(null, 'Task was updated');
        }

        return $this->sendError(422, 'Task was not updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->taskService->deleteTaskById($id)) {
            return $this->sendResponse(null, 'Task was deleted');
        }

        return $this->sendError(422, 'Task was not deleted');
    }
}
