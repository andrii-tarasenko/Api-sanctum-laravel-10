<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment as CommentModel;
use Illuminate\Support\Facades\Validator;

class Comment extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $comments = CommentModel::all();

        if ($comments->isEmpty()) {
            return $this->sendError($comments, 'There are no comments yet');
        }

        return $this->sendResponse($comments, 'All comments have been send.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @param int $task_id
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $task_id): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $comment = new CommentModel();
        $comment->content = $request->input('content');
        $comment->task_id = $task_id;
        $comment->user_id = Auth::id();

        if ($comment->save()) {
            return $this->sendResponse($comment, 'Comment added successfully.');
        }

        return $this->sendError('Comment not saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if (CommentModel::destroy($id)) {
            return $this->sendResponse([], 'Comment deleted successfully.');
        }

        return $this->sendError('Comment not found');
    }
}
