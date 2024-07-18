<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Task as TaskModel;

class Task extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = TaskModel::all();

        return $this->sendResponse($task, 'All tasks was send');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $task = new TaskModel();
        $task->title = $input['title'];
        $task->description = $input['description'];
        $task->user_id = Auth::id();
        $task->save();

        return $this->sendResponse($task, 'New task was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $task = TaskModel::findOrFail($id);

        if (is_null($task)) {
            return $this->sendError('Task was not found');
        }

        return $this->sendResponse($task, 'title - ' . $task['title'] . ' was send');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
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
        $task =TaskModel::find($id);
        $task->update($input);

        return $this->sendResponse($task, 'Task was updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $task = TaskModel::find($id);

        if (is_null($task)) {
            return $this->sendError('Task was not found');
        }
        $task->delete();

        return $this->sendResponse($id, 'Task was deleted');
    }
}
