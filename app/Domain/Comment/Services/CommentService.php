<?php

namespace Domain\Comment\Services;

use Domain\Comment\Models\Comment;
use Domain\Comment\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    private CommentRepository $commentRepository;

    /**
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param string $content
     *
     * @param int $taskId
     *
     * @return bool
     */
    public function createComment(string $content, int $taskId): bool
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->task_id = $taskId;
        $comment->user_id = auth()->id();

        return $this->commentRepository->createComment($comment);
    }

    /**
     * @return Collection|null
     */
    public function getAllComments(): ?Collection
    {
        return $this->commentRepository->getAllComments();
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteComment(int $id): int
    {
        return $this->commentRepository->deleteComment($id);
    }
}
