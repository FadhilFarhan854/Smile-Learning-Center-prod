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
    
    // Check absens table structure
    $stmt = $pdo->query("DESCRIBE absens");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nColumns in 'absens' table:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Check if there are any records
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM absens");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal records in absens table: {$result['count']}\n";
    
    // Try to insert a test record (will rollback)
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO absens (tanggal_absen, siswa_id, status, pertemuan, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute(['2024-01-01', 1, 'masuk', '1']);
        echo "\nTest insert successful - pertemuan column exists!\n";
        $pdo->rollback();
    } catch (Exception $e) {
        $pdo->rollback();
        echo "\nTest insert failed: " . $e->getMessage() . "\n";
        
        // Try without pertemuan column
        try {
            $stmt = $pdo->prepare("INSERT INTO absens (tanggal_absen, siswa_id, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute(['2024-01-01', 1, 'masuk']);
            echo "Test insert without pertemuan successful - pertemuan column missing!\n";
            $pdo->rollback();
        } catch (Exception $e2) {
            echo "Test insert without pertemuan also failed: " . $e2->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
