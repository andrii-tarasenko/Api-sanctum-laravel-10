<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Domain\Comment\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Comment extends BaseController
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $comments = $this->commentService->getAllComments();

        if ($comments === null) {
            return $this->sendError($comments, 'There are no comments yet');
        }

        return $this->sendResponse($comments, 'All comments have been send.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @param int $taskId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $taskId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, $validator->errors());
        }

        if ($this->commentService->createComment($request['content'], $taskId)) {
            return $this->sendResponse(null, 'Comment added successfully.');
        }

        return $this->sendError(422, 'Comment not saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($this->commentService->deleteComment($id)) {
            return $this->sendResponse(null, 'Comment deleted successfully.');
        }

        return $this->sendError(422, 'Comment not found');
    }
}
