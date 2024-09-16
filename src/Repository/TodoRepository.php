<?php

namespace App\Repository;

use App\Model\TodoModel;
use App\database;
use PDO;
use Throwable;

class TodoRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM todos");
            $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($todoData) {
                return new TodoModel($todoData);
            }, $todos);
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch Todos: ' . $e->getMessage());
        }
    }

    public function getById(int $id): ?TodoModel
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM todos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $todoData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $todoData ? new TodoModel($todoData) : null;
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch Todo by ID: ' . $e->getMessage());
        }
    }

    public function create(TodoModel $todo): int
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO todos (title, completed) VALUES (:title, :completed)");
            $stmt->bindParam(':title', $todo->title, PDO::PARAM_STR);
            $stmt->bindParam(':completed', $todo->completed, PDO::PARAM_BOOL);
            $stmt->execute();
            return (int)$this->db->lastInsertId();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to create todo: ' . $e->getMessage());
        }
    }

    public function update(TodoModel $todo): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE todos SET title = :title, completed = :completed WHERE id = :id");
            $stmt->bindParam(':id', $todo->id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $todo->title, PDO::PARAM_STR);
            $stmt->bindParam(':completed', $todo->completed, PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to update todo: ' . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM todos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to delete todo: ' . $e->getMessage());
        }
    }

    public function getTodoUsers(int $todoId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id, u.username, u.email
                FROM users u
                JOIN user_todos ut ON u.id = ut.user_id
                WHERE ut.todo_id = :id
            ");
            $stmt->bindParam(':id', $todoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch todo users: ' . $e->getMessage());
        }
    }
}
