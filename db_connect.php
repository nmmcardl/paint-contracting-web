<?php
require __DIR__ . '/vendor/autoload.php'; // Composer autoload

// Get connection string from env
$mongoUri = getenv('mongodb+srv://web_user:WebTest123@cluster0.ez5e6yf.mongodb.net/?appName=Cluster0'); // set this in Render and locally

if (!$mongoUri) {
    die("MONGO_URI environment variable is not set.\n");
}

try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase('paint_contracting');

    // For testing: list collections
    $collections = $db->listCollections();
    echo "<h3>Connected to paint_contracting</h3><ul>";
    foreach ($collections as $c) {
        echo "<li>" . htmlspecialchars($c->getName()) . "</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p style='color:red'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
