<?php

namespace App\Repositories;

use App\Models\Task;
use Core\Database\DatabaseInterface;

class TaskRepository
{
    /**
     * @var DatabaseInterface
     */
    protected $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function getAllTasks(): array
    {
        return $this->database->query("SELECT * FROM `tasks` ORDER BY `id`");
    }

    public function createTask(Task $task): bool
    {
        $query = "INSERT INTO `tasks` (`task`, `status`, `created_at`, `updated_at`) VALUES (:task, :status, NOW(), NOW())";
        $params = [
            ':task' => $task->task,
            ':status' => $task->status ?? 'undone'
        ];
        return $this->database->execute($query, $params);
    }

    public function updateTask(Task $task): bool
    {
        $query = "UPDATE `tasks` SET `task` = :task, `status` = :status, `updated_at` = NOW() WHERE `id` = :id";
        $params = [
            ':id' => $task->id,
            ':task' => $task->task,
            ':status' => $task->status ?? 'undone'
        ];
        return $this->database->execute($query, $params);
    }

    public function getTask(int $id): array
    {
        $query = "SELECT * FROM `tasks` WHERE `id` = :id";
        $params = [':id' => $id];
        return $this->database->query($query, $params);
    }

    public function deleteTask(int $id): bool
    {
        $query = "DELETE FROM `tasks` WHERE `id` = :id";
        $params = [':id' => $id];
        return $this->database->execute($query, $params);
    }
}
