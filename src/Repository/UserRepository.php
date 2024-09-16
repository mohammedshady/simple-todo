<?php
namespace App\Repository;

use App\Model\UserModel;
use App\Database;
use PDO;
use Throwable;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($userData) {
                return new UserModel($userData);
            }, $users);
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch Users: ' . $e->getMessage());
        }
    }

    public function getById(int $id): ?UserModel
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userData ? new UserModel($userData) : null;
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch User by ID: ' . $e->getMessage());
        }
    }

    public function create(UserModel $user): int
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email) VALUES (:username, :email)");
            $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$this->db->lastInsertId();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to create user: ' . $e->getMessage());
        }
    }

    public function update(UserModel $user): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
            $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $user->email, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to update user: ' . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to delete user: ' . $e->getMessage());
        }
    }

    public function getUserTodos(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT t.id, t.title, t.completed
                FROM todos t
                JOIN user_todos ut ON t.id = ut.todo_id
                WHERE ut.user_id = :id
            ");
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to fetch user todos: ' . $e->getMessage());
        }
    }
}