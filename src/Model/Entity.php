<?php
namespace App\Model;
use App\Database;
use PDO;


abstract class Entity {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getLastInsertId(): int {
        return (int)$this->db->lastInsertId();
    }

    abstract public function getAll(): array;
    abstract public function getById(int $id): array;
    abstract public function create(array $data): int;
    abstract public function update(array $data): bool;
    abstract public function delete(int $id): bool;
}



