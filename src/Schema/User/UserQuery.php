<?php

namespace App\Schema\User;

use App\Repository\UserRepository;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserQuery
{
    public static function fields()
    {
        return [
            'users' => [
                'type' => Types::result(Types::list(Types::user())),
                'resolve' => static function (): array {
                    $userRepository = new UserRepository();
                    $result = $userRepository->getAll();
                    if (empty($result)) {
                        return ['message' => 'Users not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return ['list' => $result];
                    }
                },
            ],
            'user' => [
                'type' => Types::result(Types::user()),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args) {
                    $userRepository = new UserRepository();
                    $result = $userRepository->getById($args['id']);

                    if (!$result) {
                        return ['message' => 'User not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return $result;
                    }
                },
            ],
        ];
    }
}
