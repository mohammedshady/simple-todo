<?php
namespace App\Schema\Todo;

use App\Model\TodoModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class TodoQuery {
    public static function fields() {
        return [
            'todos' => [
                'type' => Type::listOf(Types::todo()),
                'resolve' => static function (): array {
                    $todoModel = new TodoModel();
                    return  $todoModel->getAll();
                },
            ],
            'todo' => [
                'type' => Types::result(Types::todo()),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args) :array {
                    $todoModel = new TodoModel();
                    $result = $todoModel->getById($args['id']);
                    if (empty($result)) {
                        return ['message' => 'Todo not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return $result;
                    }
                },
            ],
        ];
    }
}
