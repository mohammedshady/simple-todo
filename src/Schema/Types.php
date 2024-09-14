<?php
namespace App\Schema;

use App\Schema\User\UserType;
use App\Schema\List\ListType;
use App\Schema\Todo\TodoType;
use App\Schema\Error\ErrorType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;
use GraphQL\Type\Definition\ObjectType;

class Types
{
    private static $todoType;
    private static $userType;
    private static $errorType;
    private static $resultType;
    private static $listType;


    public static function todo()
    {
        return self::$todoType ?: (self::$todoType = TodoType::type(function() {
            return self::user();
        }));
    }

    public static function user()
    {
        return self::$userType ?: (self::$userType = UserType::type(function() {
            return self::todo();
        }));
    }

    public static function error()
    {
        return self::$errorType ?: (self::$errorType = ErrorType::type());
    }
    public static function list($type)
    {
        $typeName = $type->name;
        return new ObjectType([
            'name' => "{$typeName}List",
            'fields' => [
                'list' => ['type' => Type::listOf($type)],
            ]
        ]);
    }

    public static function result($type)
    {   
        $typeName = $type->name;
        return new UnionType([
            'name' => "{$typeName}Result",
            'types' => [$type, self::error()],
            'resolveType' => function ($root) use ($type) {
                return isset($root['code']) ? self::error() : $type;
            }
        ]);
    }

}