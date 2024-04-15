<?php

namespace App\Database;

use PDO;
use Dotenv\Dotenv;
use PDOException;

class Connection
{
    public static function get(): PDO
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Determine if we are in test mode (can be set in phpunit.xml or dynamically before tests run)
        $isTestMode = getenv('APP_ENV') === 'test';

        // Choose the database configuration based on the mode
        if ($isTestMode) {
            $db = $_ENV['DB_TEST_DATABASE'];
            $user = $_ENV['DB_TEST_USERNAME'];
            $pass = $_ENV['DB_TEST_PASSWORD'];
            $host = $_ENV['DB_TEST_HOST'];
            $port = $_ENV['DB_TEST_PORT'];
        } else {
            $db = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
        }
        $charset = $_ENV['DB_CHARSET'];

        $dsn = "{$_ENV['DB_CONNECTION']}:host=$host;port=$port;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Log the error message or handle it appropriately
            echo "Connection failed: " . $e->getMessage();
            exit(); // Ensure script execution stops if the connection fails
        }
    }
}
