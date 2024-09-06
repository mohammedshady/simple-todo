<?php
namespace App\Schema\NotFound;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class NotFoundType {
    public static function type() {
        return new ObjectType([
            'name' => 'NotFound',
            'fields' => function() {
                return [
                'code' => ['type' => Type::string()],
                'message' => ['type' => Type::string()],
                ];
            }
        ]);
    }
}
