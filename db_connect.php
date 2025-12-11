<?php
require __DIR__ . '/vendor/autoload.php';

$mongoUri = getenv('MONGO_URI');
if (!$mongoUri) {
    die("MONGO_URI is not set. Please configure MONGO_URI as an environment variable.\n");
}

try {
    $client = new MongoDB\Client($mongoUri);
    $db = $client->selectDatabase('paint_contracting');
    // test
    $cols = $db->listCollections();
    echo "<h3>Connected to paint_contracting</h3><ul>";
    foreach ($cols as $c) {
        echo "<li>" . htmlspecialchars($c->getName()) . "</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p style='color:red'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
