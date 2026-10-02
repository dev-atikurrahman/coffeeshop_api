<?php

/**
 * Database Configuration
 * Path: src/Config/Database.php
 */

$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue; // comment skip
        if (!str_contains($line, '=')) continue;

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        $_ENVL[$key] = $value;
        putenv("$key=$value");
    }
}

class Database
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private int $port;
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->username = getenv('DB_USERNAME') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
        $this->database = getenv('DB_DATABASE') ?: 'my_database';
        $this->port = (int)(getenv('DB_PORT') ?: 3306);
    }
}
