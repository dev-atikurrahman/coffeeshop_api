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
        $key   = trim($key);
        $value = trim($value);

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

class Database
{
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private string $port;
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->host     = $_ENV['DB_HOST']  ?? 'localhost';
        $this->db_name  = $_ENV['DB_NAME']  ?? 'coffee_shop_db';
        $this->username = $_ENV['DB_USER']  ?? 'root';
        $this->password = $_ENV['DB_PASS']  ?? '';
        $this->port     = $_ENV['DB_PORT']  ?? '3306';
    }

    public function connect(): PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }

        return $this->connection;
    }

    public function prepare(string $sql): PDOStatement
    {
        return $this->connect()->prepare($sql);
    }

    public function lastInsertId(): string
    {
        return $this->connect()->lastInsertId();
    }

    public function beginTransaction(): void
    {
        $this->connect()->beginTransaction();
    }

    public function commit(): void
    {
        $this->connect()->commit();
    }

    public function rollback(): void
    {
        $this->connect()->rollBack();
    }
}
