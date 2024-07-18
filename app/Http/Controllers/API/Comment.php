<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment as CommentModel;
use Validator;

class Comment extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $task_id)
    {
        $comments = CommentModel::all();

        if ($comments->isEmpty()) {
            return $this->sendError($comments, 'There are no comments yet');
        }

        return $this->sendResponse($comments, 'All comments have been send.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $task_id)
    {
        $validated = Validator::make($request->all(),[
            'content' => 'required|string|max:255',
        ]);

        if($validated->fails()){
            return response(['errors'=>$validated->errors()],422);
        }

        $comment = new CommentModel();
        $comment->content = $request->input('content');
        $comment->task_id = $task_id;
        $comment->user_id = Auth::id();

        if($comment->save()){
            return $this->sendResponse($comment, 'Comment added successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $comment = CommentModel::find($id);
        if (empty($comment)) {
            return $this->sendError('Comment not found');
        }

        if($comment->delete()){
            return $this->sendResponse($comment, 'Comment deleted successfully.');
        }
    }
}
