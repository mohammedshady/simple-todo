<?php
namespace App\Schema\Error;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ErrorType {
    public static function type() {
        return new ObjectType([
            'name' => 'Error',
            'fields' => function() {
                return [
                'code' => ['type' => Type::string()],
                'message' => ['type' => Type::string()],
                ];
            }
        ]);
    }
}
