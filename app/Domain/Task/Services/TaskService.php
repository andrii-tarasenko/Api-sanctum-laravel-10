<?php

namespace Domain\Task\Services;

use Domain\Task\Models\Task;
use Domain\Task\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param array $inputTaskData
     *
     * @return Task
     */
    public function createTask(array $inputTaskData): Task
    {
        $task = new Task();
        $task->title = $inputTaskData['title'];
        $task->description = $inputTaskData['description'];
        $task->user_id = Auth::id();

        return $this->taskRepository->save($task);
    }

    /**
     * @return Collection|null
     */
    public function getAllTasks(): ?Collection
    {
        return $this->taskRepository->getAll();
    }

    /**
     * @param int $id
     *
     * @return Collection|null
     */
    public function getTaskById(int $id): ?Collection
    {
        return $this->taskRepository->getById($id);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteTaskById(int $id): bool
    {
        return $this->taskRepository->deleteById($id);
    }

    /**
     * @param array $inputData
     *
     * @param int $id
     *
     * @return bool
     */
    public function updateTaskById(array $inputData, int $id): bool
    {
        return $this->taskRepository->updateTask($id, $inputData);
    }
}
