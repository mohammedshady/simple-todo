<?php

namespace App\Schema\Todo;

use App\Repository\TodoRepository;
use GraphQL\Type\Definition\Type;
use App\Schema\Types;

class TodoQuery
{
    public static function fields()
    {
        return [
            'todos' => [
                'type' => Types::result(Types::list(Types::todo())),
                'resolve' => static function (): array {
                    $todoRepository = new TodoRepository();
                    $result = $todoRepository->getAll();
                    if (empty($result)) {
                        return ['message' => 'Todos not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return ['list' => $result];
                    }
                },
            ],
            'todo' => [
                'type' => Types::result(Types::todo()),
                'args' => ['id' => Type::nonNull(Type::int())],
                'resolve' => static function ($root, array $args) {
                    $todoRepository = new TodoRepository();
                    $result = $todoRepository->getById($args['id']);
                    if (!$result) {
                        return ['message' => 'Todo not found', 'code' => 'NOT_FOUND'];
                    } else {
                        return $result;
                    }
                },
            ],
        ];
    }
}
