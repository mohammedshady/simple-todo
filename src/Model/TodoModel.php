<?php
namespace App\Model;
use App\Model\Entity;
use RuntimeException;
use PDO;
use Throwable;


class TodoModel extends Entity {

    public function getAll(): array {

        try {
            $stmt = $this->db->query("SELECT * FROM todos");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result !== false ? $result : [];
        } catch (Throwable $e) {
            //maybe use that
            throw new UserError('Failed to fetch Todos ' . $e->getMessage());
        }
    }
    public function getById(int $id): array {
        try {
            $stmt = $this->db->prepare("SELECT id, title, completed FROM todos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : [];
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to fetch todo by ID: ' . $e->getMessage());
        }
    }

    public function create(array $args): int {
        try {
            $stmt = $this->db->prepare("INSERT INTO todos (title, completed) VALUES (:title, :completed)");
            $stmt->bindParam(':title', $args['title'], PDO::PARAM_STR);
            $stmt->bindParam(':completed', $args['completed'], PDO::PARAM_BOOL);
            $stmt->execute();
            return $this->getLastInsertId();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to create todo: ' . $e->getMessage());
        }
    }

    public function update(array $args): bool {
        try {
            $stmt = $this->db->prepare("UPDATE todos SET title = :title, completed = :completed WHERE id = :id");
            $stmt->bindParam(':id', $args['id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $args['title'], PDO::PARAM_STR);
            $stmt->bindParam(':completed', $args['completed'], PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to update todo: ' . $e->getMessage());
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM todos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to delete todo: ' . $e->getMessage());
        }
    }

    public function getTodoUsers(int $todoId): array {
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
        throw new RuntimeException('Failed to fetch todo users: ' . $e->getMessage());
    }
    }
}