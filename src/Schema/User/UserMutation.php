<?php

namespace App\Schema\User;

use App\Model\UserModel;
use App\Repository\UserRepository;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserMutation
{
    public static function fields()
    {
        return [
            'addUser' => [
                'type' => Types::user(),
                'args' => [
                    'username' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                ],
                'resolve' => static function ($root, array $args): ?UserModel {
                    $userRepository = new UserRepository();
                    $user = new UserModel([
                        'username' => $args['username'],
                        'email' => $args['email']
                    ]);
                    $id = $userRepository->create($user);
                    return $userRepository->getById($id);
                },
            ],
            'deleteUser' => [
                'type' => Type::boolean(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                ],
                'resolve' => static function ($root, array $args): bool {
                    $userRepository = new UserRepository();
                    return $userRepository->delete($args['id']);
                },
            ],
            'updateUser' => [
                'type' => Types::user(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                    'username' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                ],
                'resolve' => static function ($root, array $args): ?UserModel {
                    $userRepository = new UserRepository();
                    $user = new UserModel([
                        'id' => $args['id'],
                        'username' => $args['username'],
                        'email' => $args['email']
                    ]);
                    $result = $userRepository->update($user);
                    return $result ? $userRepository->getById($args['id']) : null;
                },
            ],
        ];
    }
}
