<?php

namespace App\Model;

class TodoModel
{
    public int $id;
    public string $title;
    public bool $completed;

    public function __construct(array $data = [])
    {
        if (!isValid($data)) return;

        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->completed = $data['completed'] ?? false;
    }

    public function isValid (array $data = []): bool{

        // handle validation logic
        return true;
    }
}
