<?php
require __DIR__ . '/../config.php';

// get all collections
$collections = [];
foreach ($db->listCollections() as $c) {
    $collections[] = $c->getName();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>paint_contracting — Collections</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    a.collection{display:block;margin:8px 0}
  </style>
</head>
<body>
  <h1>paint_contracting — Collections</h1>
  <p><strong>Total:</strong> <?= count($collections) ?></p>
  <ul>
    <?php foreach ($collections as $name): ?>
      <li>
        <a class="collection" href="view_collection.php?name=<?= urlencode($name) ?>">
          <?= htmlspecialchars($name) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
