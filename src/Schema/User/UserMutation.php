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
            // You can add more mutations here, like updateUser, deleteUser, etc.
        ];
    }
}