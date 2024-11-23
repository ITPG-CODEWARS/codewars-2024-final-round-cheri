<?php

function loadEnv($filePath): void {
    if (!file_exists($filePath)) {
        die("sorry");
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

loadEnv(__DIR__ . '/config.env');
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_NAME'] ?? '';
$dbUser = $_ENV['DB_USER'] ?? '';
$dbPass = $_ENV['DB_PASS'] ?? '';

try {
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    echo 'Connection Successful!</br>';

    
    $stmt = $pdo->prepare("SELECT * FROM orders");
    $stmt->execute();
    $results = $stmt->fetchAll();
    

    if (count($results) == 0) echo 'No Data found in table';

} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo '<p class="message">Database Error</p>';
}





