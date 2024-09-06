<?php

namespace App\Controller;
use App\Model\TodoModel;
use App\Model\UserModel;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphQL {
    static public function handle() {
        try {
            // Define the Todo type
            $todoType = new ObjectType([
                'name' => 'Todo',
                'fields' => function () use (&$userType) {
                    return [
                        'id' => ['type' => Type::int()],
                        'title' => ['type' => Type::string()],
                        'completed' => ['type' => Type::boolean()],
                        'users' => [
                            'type' => Type::listOf($userType),
                            'resolve' => static function ($todo): array {
                                $todoModel = new TodoModel();
                                return $todoModel->getTodoUsers($todo['id']);
                            },
                        ],
                    ];
                }
            ]);
            
            $userType = new ObjectType([
                'name' => 'User',
                'fields' => function () use (&$todoType) {
                    return [
                        'id' => ['type' => Type::int()],
                        'username' => ['type' => Type::string()],
                        'email' => ['type' => Type::string()],
                        'todos' => [
                            'type' => Type::listOf($todoType),
                            'resolve' => static function ($user): array {
                                $userModel = new UserModel();
                                return $userModel->getUserTodos($user['id']);
                            },
                        ],
                    ];
                }
            ]);

            // Define the Query type
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'todos' => [
                        'type' => Type::listOf($todoType),
                        'resolve' => static function (): array {
                            $todoModel = new TodoModel();
                            return $todoModel->getAll();
                        },
                    ],
                    'todo' => [
                        'type' => $todoType,
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                        ],
                        'resolve' => static function ($root, array $args): array {
                            $todoModel = new TodoModel();
                            return $todoModel->getById($args['id']);
                        },
                    ],
                    'users' => [
                        'type' => Type::listOf($userType),
                        'resolve' => static function (): array {
                            $userModel = new UserModel();
                            return $userModel->getAll();
                        },
                    ],
                    'user' => [
                        'type' => $userType,
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                        ],
                        'resolve' => static function ($root, array $args): array {
                            $userModel = new UserModel();
                            return $userModel->getById($args['id']);
                        },
                    ],
                ],
            ]);

            // Define the Mutation type
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'addTodo' => [
                        'type' => $todoType,
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
                            if ($result) {
                                return $todoModel->getById($todoModel->getLastInsertId());
                            }
                            return null;
                        },
                    ],
                    'updateTodo' => [
                        'type' => $todoType,
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
                    'addUser' => [
                        'type' => $userType,
                        'args' => [
                            'username' => Type::nonNull(Type::string()),
                            'email' => Type::nonNull(Type::string()),
                        ],
                        'resolve' => static function ($root, array $args):array {
                            $userModel = new UserModel();
                            $result = $userModel->create([
                                'username' => $args['username'],
                                'email' => $args['email']
                            ]);
                            if ($result) {
                                return $userModel->getById($userModel->getLastInsertId());
                            }
                            return null;
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
                        'type' => $userType,
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
                ],
            ]); 

            // Create the schema
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
        
            // Handle the GraphQL query execution
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
        
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}
