<?php
namespace App\Schema\User;

use App\Model\UserModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserQuery {
    public static function fields() {
        return [
            'users' => [
                'type' => Type::listOf(Types::user()),
                'resolve' => static function (): array {
                    $userModel = new UserModel();
                    return $userModel->getAll();
                },
            ],
            'user' => [
                'type' => Types::user(),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args): array {
                    $userModel = new UserModel();
                    return $userModel->getById($args['id']);
                },
            ],
        ];
    }
}