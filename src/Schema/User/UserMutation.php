<?php
namespace App\Schema\User;

use App\Model\UserModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class UserMutation {
    public static function fields() {
        return [
            'addUser' => [
                'type' => Types::user(),
                'args' => [
                    'username' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                ],
                'resolve' => static function ($root, array $args): ?array {
                    $userModel = new UserModel();
                    $result = $userModel->create([
                        'username' => $args['username'],
                        'email' => $args['email']
                    ]);
                    return $result ? $userModel->getById($userModel->getLastInsertId()) : null;
                },
            ],
            'deleteUser' => [
                'type' => Type::boolean(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                ],
                'resolve' => static function ($root, array $args): bool {
                    $userModel = new UserModel();
                    return $userModel->delete($args['id']);
                },
            ],
            'updateUser' => [
                'type' => Types::user(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                    'username' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                ],
                'resolve' => static function ($root, array $args): array {
                    $userModel = new UserModel();
                    $result = $userModel->update([
                        'id' => $args['id'],
                        'username' => $args['username'],
                        'email' => $args['email']
                    ]);
                    if ($result) {
                        return $userModel->getById($args['id']);
                    }
                    return null;
                },
            ],
        ];
    }
}