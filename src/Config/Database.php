<?php
/**
 * Database Configuration
 * Path: src/Config/Database.php
*/

$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = parse_ini_file($envFile);
    $dbHost = $env['DB_HOST'] ?? 'localhost';
    $dbName = $env['DB_NAME'] ?? 'coffee_shop_db';
    $dbUser = $env['DB_USER'] ?? 'root';
    $dbPass = $env['DB_PASS'] ?? '';
    $dbPort = $env['DB_PORT'] ?? 3306;
} else {
    // Default values if .env file is not found
    $dbHost = 'localhost';
    $dbName = 'coffee_shop_db';
    $dbUser = 'root';
    $dbPass = '';
    $dbPort = 3306;
}

?>