<?php
namespace App\Schema\Todo;

use App\Model\TodoModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Todo\TodoType;
use App\Schema\Types;

class TodoQuery {
    public static function fields() {
        return [
            'todos' => [
                'type' => Type::listOf(Types::todo()),
                'resolve' => static function (): array {
                    $todoModel = new TodoModel();
                    return $todoModel->getAll();
                },
            ],
            'todo' => [
                'type' => Types::todo(),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args): array {
                    $todoModel = new TodoModel();
                    return $todoModel->getById($args['id']);
                },
            ],
        ];
    }
}
