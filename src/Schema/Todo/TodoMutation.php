<?php
namespace App\Schema\Todo;

use App\Model\TodoModel;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class TodoMutation {
    public static function fields() {
        return [
            'addTodo' => [
                'type' => Types::todo(),
                'args' => [
                    'title' => Type::nonNull(Type::string()),
                    'completed' => Type::boolean(),
                ],
                'resolve' => static function ($root, array $args): ?array {
                    $todoModel = new TodoModel();
                    $result = $todoModel->create([
                        'title' => $args['title'],
                        'completed' => $args['completed'] ?? false
                    ]);
                    return $result ? $todoModel->getById($todoModel->getLastInsertId()) : null;
                },
            ],
            // You can add more mutations here, like updateTodo, deleteTodo, etc.
        ];
    }
}