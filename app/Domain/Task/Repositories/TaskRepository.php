<?php

namespace Domain\Task\Repositories;

use Domain\Task\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    /**
     * @param Task $task
     *
     * @return Task
     */
    public function save(Task $task): Task
    {
        $task->save();

        return $task;
    }

    /**
     * @return Collection|null
     */
    public function getAll(): ?Collection
    {
        return Task::all();
    }

    /**
     * @param int $id
     *
     * @return Collection|null
     */
    public function getById(int $id): ?Collection
    {
        return Task::all()->find($id);
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function deleteById(int $id): int
    {
        return Task::destroy($id);
    }

    /**
     * @param Task $task
     *
     * @param array $inputData
     *
     * @return bool
     */
    public function updateTask(int $id, array $inputData): bool
    {
        return Task::all()->find($id)->update($inputData);
    }
}
