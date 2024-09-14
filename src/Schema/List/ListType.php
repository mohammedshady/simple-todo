<?php
namespace App\Schema\List;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ListType {
    public static function type($type) {
        return new ObjectType([
        'name' => 'List',
        'fields' => function() use ($type) {
            return [
                'list' => ['type' => Type::listOf($type)],
            ];
        }
        ]);
    }
}


