<?php
namespace App\Model;
use App\Model\Entity;
use GraphQL\Error\UserError;
use App\Exceptions\NotFoundError;
use RuntimeException;
use PDO;
use Throwable;


class UserModel extends Entity {

public function getAll(): array {
    try {
        $stmt = $this->db->query("SELECT * FROM users");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result !== false ? $result : [];
    } catch (Throwable $e) {
        //maybe use that
        throw new UserError('Failed to fetch Users ' . $e->getMessage());
    }
}

public function getById(int $id): array {
   try{
      $stmt = $this->db->prepare("SELECT id, username, email FROM users WHERE id = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result !== false ? $result : [];
    } catch (Throwable $e) {
    //maybe use that
    throw new UserError('Failed to fetch User by ID: ' . $e->getMessage());
}
    
}

public function create(array $args): int {
    try {
        $stmt = $this->db->prepare("INSERT INTO users (username, email) VALUES (:username, :email)");
        $stmt->bindParam(':username', $args['username'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $args['email'], PDO::PARAM_STR);
        $stmt->execute();
        return $this->getLastInsertId();
    } catch (Throwable $e) {
        throw new RuntimeException('Failed to create user: ' . $e->getMessage());
    }
}

public function update(array $args): bool {
    try {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $stmt->bindParam(':id', $args['id'], PDO::PARAM_INT);
        $stmt->bindParam(':username', $args['username'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $args['email'], PDO::PARAM_STR);
        return $stmt->execute();
    } catch (Throwable $e) {
        throw new RuntimeException('Failed to update user: ' . $e->getMessage());
    }
}

public function delete(int $id): bool {
    try {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (Throwable $e) {
        throw new RuntimeException('Failed to delete user: ' . $e->getMessage());
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
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result !== false ? $result : [];
    } catch (Throwable $e) {
        throw new RuntimeException('Failed to fetch user todos: ' . $e->getMessage());
    }
}

}
