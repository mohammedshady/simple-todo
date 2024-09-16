<?php
namespace App\Model;

class UserModel
{
    public int $id;
    public string $username;
    public string $email;
    public array $todos = [];

    public function __construct(array $data = [])
    {
        if (!$this->isValid($data)) return;

        $this->id = $data['id'] ?? 0;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
    }
    public function setTodos(array $todos): void
    {
        $this->todos = array_map(function ($todoData) {
            return $todoData instanceof TodoModel ? $todoData : new TodoModel($todoData);
        }, $todos);
    }
    public function isValid (array $data = []): bool{

        // handle validation logic
        return true;
    }
    // can use private and return data using getters
}