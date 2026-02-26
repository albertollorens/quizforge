<?php
namespace App\Config;

use PDO;
use Exception;

class Database {
    public static function connect(): PDO {
        // ---------- 2. Llegir variables d’entorn ----------
        $host = Settings::dbHost() ?: 'localhost';
        $db   = Settings::dbName() ?: 'quizforge';
        $user = Settings::dbUser() ?: 'quizforge';
        $pass = Settings::dbPass() ?: 'qf2025*';

        if (!$host || !$db || !$user) {
            throw new Exception("Database configuration incomplete. Check environment variables.");
        }

        // ---------- 3. Connexió PDO ----------
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem'
        ];

        return new PDO($dsn, $user, $pass, $options);
    }
}

