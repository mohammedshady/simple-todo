<?php
namespace App\Schema;

use App\Schema\User\UserType;
use App\Schema\Todo\TodoType;
use App\Schema\NotFound\NotFoundType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;

class Types
{
    private static $todoType;
    private static $userType;
    private static $notFoundType;
    private static $userResultType;

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

    public static function notFound()
    {
        return self::$notFoundType ?: (self::$notFoundType = NotFoundType::type());
    }

    public static function userResult()
    {
        return self::$userResultType ?: (self::$userResultType = new UnionType([
            'name' => 'UserResultType',
            'types' => [self::user(), self::notFound()],
            'resolveType' => function ($root) {
                //to be changed
                if (isset($root['code'])) {
                    return self::notFound();
                }
                else {
                    return self::user();
                }
            }
        ]));
    }
}