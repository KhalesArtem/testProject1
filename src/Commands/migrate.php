<?php

$autoloadPath = __DIR__ . '/../../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    die("Autoload file not found. Please run 'composer install' first.\n");
}

require_once $autoloadPath;

use Dotenv\Dotenv;
use App\Models\Database;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $db = Database::getInstance()->getConnection();

    $migrationFile = __DIR__ . '/../../migrations/example.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found at: $migrationFile");
    }

    $sql = file_get_contents($migrationFile);
    if ($sql === false) {
        throw new Exception("Could not read migration file: $migrationFile");
    }

    $db->exec($sql);
    if (php_sapi_name() === 'cli') {
        echo "Migration completed successfully!\n";
    }
} catch (Exception $e) {
    if (php_sapi_name() === 'cli') {
        echo "Migration failed: " . $e->getMessage() . "\n";
    }
    exit(1);
}
