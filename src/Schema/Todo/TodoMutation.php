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
            'updateTodo' => [
                'type' =>  Types::todo(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                    'title' => Type::nonNull(Type::string()),
                    'completed' => Type::nonNull(Type::boolean()),
                ],
                'resolve' => static function ($root, array $args): ?array {
                    $todoModel = new TodoModel();
                    $result = $todoModel->update([
                        'id' => $args['id'],
                        'title' => $args['title'],
                        'completed' => $args['completed']
                    ]);
                    if ($result) {
                        return $todoModel->getById($args['id']);
                    }
                    return null;
                },
            ],
            'deleteTodo' => [
                'type' => Type::boolean(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                ],
                'resolve' => static function ($root, array $args): bool {
                    $todoModel = new TodoModel();
                    return $todoModel->delete($args['id']);
                },
            ],
        ];
    }
}