<?php
namespace App\Schema\Todo;

use App\Model\TodoModel;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

use App\Schema\Types;

class TodoType {
    public static function type() {
        return new ObjectType([
            'name' => 'Todo',
            'fields' => function(){
                return [
                    'id' => ['type' => Type::int()],
                    'title' => ['type' => Type::string()],
                    'completed' => ['type' => Type::boolean()],
                    'users' => [
                        'type' => Type::listOf(Types::user()),  
                        'resolve' => static function ($todo): array {
                            $todoModel = new TodoModel();
                            return $todoModel->getTodoUsers($todo['id']);
                        },
                    ],
                ];
            }
        ]);
    }
}