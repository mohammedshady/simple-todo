<?php
namespace App\Schema\User;

use App\Model\UserModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserQuery {
    public static function fields() {
        return [
            'users' => [
                'type' => Types::result(Types::list(Types::user())),
                'resolve' => static function (): array {
                    $userModel = new UserModel();
                    $result = $userModel->getAll();
                    if (empty($result)) {
                        return ['message' => 'Users not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return ['list'=> $result]; 
                    }
                },
            ],
            'user' => [
                'type' => Types::result(Types::user()),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args): array {
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
