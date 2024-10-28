<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    private $maxRetries = 20;
    private $retryDelay = 3;

    private function __construct()
    {
        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
            // Защита от SQL-инъекций
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $retries = 0;
        while ($retries < $this->maxRetries) {
            try {
                $this->connection = new PDO($dsn, $user, $pass, $options);
                return;
            } catch (PDOException $e) {
                $retries++;
                if ($retries === $this->maxRetries) {
                    throw new PDOException("Connection failed after {$this->maxRetries} attempts: " . $e->getMessage());
                }
                sleep($this->retryDelay);
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Метод для безопасного выполнения запросов
    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }
}
