<?php
namespace App\Lib;

use PDO;
use App\Config\Config;

class Database {
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . Config::$db_host . ';dbname=' . Config::$db_name . ';charset=utf8mb4';
            self::$pdo = new PDO($dsn, Config::$db_user, Config::$db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
