<?php
namespace App\Schema;

use App\Schema\User\UserType;
use App\Schema\Todo\TodoType;
use GraphQL\Type\Definition\Type;

class Types
{
    private static $todoType;
    private static $userType;

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
}