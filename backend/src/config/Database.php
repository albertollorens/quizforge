<?php
namespace App\Config;

use PDO;
use Dotenv\Dotenv;
use Exception;

class Database {
    public static function connect(): PDO {
        // ---------- 1. Carregar .env local si existeix ----------
        $envPath = __DIR__ . '/../../';
        if (file_exists($envPath . '.env')) {
            $dotenv = Dotenv::createImmutable($envPath, '.env');
            $dotenv->safeLoad(); // safeLoad no llença error si no existeix
        }

        // ---------- 2. Llegir variables d’entorn ----------
        $host = getenv('DB_HOST') ?: 'localhost';
        $db   = getenv('DB_NAME') ?: 'quizforge';
        $user = getenv('DB_USER') ?: 'quizforgeuser';
        $pass = getenv('DB_PASS') ?: 'qf2025*';
        $port = getenv('DB_PORT') ?: 3306;

        if (!$host || !$db || !$user) {
            throw new Exception("Database configuration incomplete. Check environment variables.");
        }

        // ---------- 3. Connexió PDO ----------
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}

