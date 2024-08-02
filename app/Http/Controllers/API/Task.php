<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Task as TaskModel;

class Task extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $task = TaskModel::all();

        return $this->sendResponse($task, 'All tasks was send');
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
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();

        $task = new TaskModel();
        $task->title = $input['title'];
        $task->description = $input['description'];
        $task->user_id = Auth::id();

        if ($task->save()) {
            return $this->sendResponse($task, 'New task was created');
        }

        return $this->sendError('Task was not created');
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
        $task = TaskModel::all()->find($id);

        if ($task->isEmpty()) {
            return $this->sendError('Task was not found');
        }

        return $this->sendResponse($task, 'title - ' . $task['title'] . ' was send');
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
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['user_id'] = Auth::id();
        $task = TaskModel::all()->find($id);
        $task->update($input);

        return $this->sendResponse($task, 'Task was updated');
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
        if (TaskModel::destroy($id)) {
            return $this->sendResponse($id, 'Task was deleted');
        }

        return $this->sendError('Task was not found');
    }
}
