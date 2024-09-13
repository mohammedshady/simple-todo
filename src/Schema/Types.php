<?php
namespace App\Schema;

use App\Schema\User\UserType;
use App\Schema\Todo\TodoType;
use App\Schema\Error\ErrorType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;

class Types
{
    private static $todoType;
    private static $userType;
    private static $errorType;
    private static $resultType;
    private static $listResultType;


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

    public static function result($type)
    {
        return (self::$resultType = new UnionType([
            'name' => $type->name.'ResultType',
            'types' => [$type, self::error()],
            'resolveType' => function ($root) use ($type) {
                //to be changed
                if (isset($root['code'])) {
                    return self::error();
                }
                else {
                    return $type;
                }
            }
        ]));
    }
}