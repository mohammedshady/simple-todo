<?php
namespace App\Model;

class UserModel
{
    public int $id;
    public string $username;
    public string $email;

    public function __construct(array $data = [])
    {
        if (!isValid($data)) return;

        $this->id = $data['id'] ?? 0;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
    }
    public function isValid (array $data = []): bool{

        // handle validation logic
        return true;
    }
    // can use private and return data using getters
}