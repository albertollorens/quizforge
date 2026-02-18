<?php
namespace App\Config;

use PDO;
use Dotenv;

class Database {
    public static function connect(): PDO {
        // Looing for .env at the root directory
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../', '.env');
        $dotenv->load(); 
        return new PDO(
            "mysql:host=localhost;dbname=quizforge;charset=utf8",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
