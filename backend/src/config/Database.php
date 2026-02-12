<?php
namespace App\Config;

use PDO;

class Database {
    public static function connect(): PDO {
        return new PDO(
            "mysql:host=localhost;dbname=quizforge;charset=utf8",
            "quizforge",
            "qf2025*",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
