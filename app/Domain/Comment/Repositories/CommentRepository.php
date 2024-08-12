<?php

namespace Domain\Comment\Repositories;

use Domain\Comment\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository
{
    /**
     * @return Collection
     */
    public function getAllComments(): Collection
    {
        return Comment::all();
    }

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function createComment(Comment $comment): bool
    {
        return $comment->save();
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteComment(int $id): int
    {
        return Comment::destroy($id);
    }
}
