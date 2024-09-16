<?php

namespace App\Schema\Todo;

use App\Model\TodoModel;
use App\Repository\TodoRepository;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class TodoMutation
{
    public static function fields()
    {
        return [
            'addTodo' => [
                'type' => Types::todo(),
                'args' => [
                    'title' => Type::nonNull(Type::string()),
                    'completed' => Type::boolean(),
                ],
                'resolve' => static function ($root, array $args): ?TodoModel {
                    $todoRepository = new TodoRepository();
                    $todo = new TodoModel([
                        'title' => $args['title'],
                        'completed' => $args['completed'] ?? false
                    ]);
                    $id = $todoRepository->create($todo);
                    return $todoRepository->getById($id);
                },
            ],
            'updateTodo' => [
                'type' =>  Types::todo(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                    'title' => Type::nonNull(Type::string()),
                    'completed' => Type::nonNull(Type::boolean()),
                ],
                'resolve' => static function ($root, array $args): ?TodoModel {
                    $todoRepository = new TodoRepository();
                    $todo = new TodoModel([
                        'id' => $args['id'],
                        'title' => $args['title'],
                        'completed' => $args['completed']
                    ]);
                    $result = $todoRepository->update($todo);
                    return $result ? $todoRepository->getById($args['id']) : null;
                },
            ],
            'deleteTodo' => [
                'type' => Type::boolean(),
                'args' => [
                    'id' => Type::nonNull(Type::int()),
                ],
                'resolve' => static function ($root, array $args): bool {
                    $todoRepository = new TodoRepository();
                    return $todoRepository->delete($args['id']);
                },
            ],
        ];
    }
}
