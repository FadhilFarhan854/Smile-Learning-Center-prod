<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Create database connection
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n";
    
    // Check moduls table structure
    $stmt = $pdo->query("DESCRIBE moduls");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nColumns in 'moduls' table:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Check if there are any records
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM moduls");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal records in moduls table: {$result['count']}\n";
    
    // Try to insert a test record (will rollback)
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO moduls (nama, kategori, level, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute(['Test Modul', 'Test Kategori', 1]);
        echo "\nTest insert successful!\n";
        $pdo->rollback();
    } catch (Exception $e) {
        $pdo->rollback();
        echo "\nTest insert failed: " . $e->getMessage() . "\n";
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
