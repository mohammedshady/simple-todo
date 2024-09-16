<?php

namespace App\Model;

use App\Model\UserModel;

class TodoModel
{
    public int $id;
    public string $title;
    public bool $completed;
    public array $users = [];

    public function __construct(array $data = [])
    {
        if (!$this->isValid($data)) return;

        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->completed = $data['completed'] ?? false;
    }
    public function setUsers(array $users): void
    {
        $this->users = array_map(function ($userData) {
            return $userData instanceof UserModel ? $userData : new UserModel($userData);
        }, $users);
    }
    public function isValid (array $data = []): bool{

        // handle validation logic
        return true;
    }
}
