<?php
// config.php - shared DB connection
require __DIR__ . '/vendor/autoload.php';

$mongoUri = getenv('MONGO_URI') ?: 'mongodb://localhost:27017';
$dbName   = 'paint_contracting';

if (!$mongoUri) {
    die("MONGO_URI not set in environment.");
}

try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase($dbName);
} catch (Exception $e) {
    // In dev show message; in production log instead
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
