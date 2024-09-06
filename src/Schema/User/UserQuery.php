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
                'type' => Types::userResult(),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args) {
                    $userModel = new UserModel();
                    $result = $userModel->getById($args['id']);

                    if (empty($result)) {
                        return ['message' => 'User not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return $result;
                    }
                },
            ],
        ];
    }
}