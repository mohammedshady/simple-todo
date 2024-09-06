<?php

namespace App;

use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO('mysql:host=localhost;dbname=todo_app', 'root', '');
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }
    
}
