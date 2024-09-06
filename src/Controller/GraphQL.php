<?php
namespace App\Controller;
use App\Schema\Todo\TodoQuery;
use App\Schema\Todo\TodoMutation;
use App\Schema\Todo\TodoType;
use App\Schema\User\UserQuery;
use App\Schema\User\UserMutation;
use App\Schema\User\UserType;

use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\SchemaConfig;
use GraphQL\GraphQL as GraphQLBase;
use Throwable;

class GraphQL {
    static public function handle() {
        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => 
                    array_merge(
                        UserQuery::fields(),
                        TodoQuery::fields()
                    )
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' =>
                    array_merge(
                        UserMutation::fields(),
                        TodoMutation::fields()
                    )
            ]);
            
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variables = $input['variables'] ?? null;

            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variables);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = ['error' => ['message' => $e->getMessage()]];
        }

        header('Content-Type: application/json');
        echo json_encode($output);
    }
}
