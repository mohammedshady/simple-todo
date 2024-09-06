<?php
namespace App\Schema\User;

use App\Model\UserModel;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserType {
    public static function type($todoType) {
        return new ObjectType([
            'name' => 'User',
            'fields' => function() use ($todoType) {
                return [
                    'id' => ['type' => Type::int()],
                    'username' => ['type' => Type::string()],
                    'email' => ['type' => Type::string()],
                    'todos' => [
                        'type' => Type::listOf($todoType()),
                        'resolve' => static function ($user): array {
                            $userModel = new UserModel();
                            return $userModel->getUserTodos($user['id']);
                        },
                    ],
                ];
            }
        ]);
    }
}