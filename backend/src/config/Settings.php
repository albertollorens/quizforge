<?php

namespace App\Config;

use Dotenv\Dotenv;

class Settings
{
    private static bool $loaded = false;

    public static function init(): void
    {
        self::loadEnv();
    }

    private static function loadEnv(): void
    {
        if (self::$loaded) {
            return;
        }

        $envPath = __DIR__ . '/../../';

        if (file_exists($envPath . '.env')) {
            $dotenv = Dotenv::createImmutable($envPath);
            $dotenv->load();
        }

        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        self::loadEnv();
        return $_ENV[$key] ?? $default;
    }

    public static function jwtSecret(): string
    {
        return self::get('JWT_SECRET');
    }

    public static function openaiKey(): string
    {
        return self::get('OPENAI_API_KEY');
    }

    public static function dbHost(): string
    {
        return self::get('DB_HOST');
    }

    public static function dbName(): string
    {
        return self::get('DB_NAME');
    }

    public static function dbUser(): string
    {
        return self::get('DB_USER');
    }

    public static function dbPass(): string
    {
        return self::get('DB_PASS');
    }
}