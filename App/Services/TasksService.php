<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Core\Data\Validator;
use Core\Exception\Exceptions\ValidationException;

class TasksService
{
    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(): array
    {
        return Task::map(
            $this->taskRepository->getAllTasks()
        );
    }

    public function store(array $data): void
    {
        $validator = new Validator($data, [
            'task' => [
                function ($value) {
                    if (empty($value)) {
                        return 'Task cannot be empty';
                    }
                    return null;
                }
            ]
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
        $this->taskRepository->createTask(
            new Task($validator->validated())
        );
    }

    public function update(array $data): void
    {
        $validator = new Validator($data, [
            'task' => [
                function ($value) {
                    if (empty($value)) {
                        return 'Task cannot be empty';
                    }
                    return null;
                },
            ],
            'id' => [
                function ($value) {
                    if (!is_numeric($value) || $value <= 0) {
                        return 'Invalid task ID';
                    }
                    return null;
                },
                function ($value) {
                    $task = $this->taskRepository->getTask($value);
                    if (empty($task)) {
                        return 'Task not found';
                    }
                    return null;
                }
            ],
            'status' => [
                function ($value) {
                    if (empty($value)) {
                        return null;
                    }
                    if (!in_array($value, ['done', 'undone'])) {
                        return 'Status must be either "done" or "undone"';
                    }
                    return null;
                }
            ]
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
        $task = new Task($validator->validated());
        $this->taskRepository->updateTask($task);
    }

    public function destroy($id): void
    {
        $validator = new Validator(['id' => $id], [
            'id' => [
                function ($value) {
                    if (!is_numeric($value) || $value <= 0) {
                        return 'Invalid task ID';
                    }
                    return null;
                },
                function ($value) {
                    $task = $this->taskRepository->getTask($value);
                    if (empty($task)) {
                        return 'Task not found';
                    }
                    return null;
                }
            ]
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
        $this->taskRepository->deleteTask($id);
    }

}
