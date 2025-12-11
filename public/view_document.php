<?php
require __DIR__ . '/../config.php';

$collectionName = isset($_GET['collection']) ? (string) $_GET['collection'] : '';
$idParam = isset($_GET['id']) ? (string) $_GET['id'] : '';

if ($collectionName === '' || $idParam === '') {
    header('Location: index.php');
    exit;
}

$collection = $db->selectCollection($collectionName);

// try to convert to ObjectId if looks like one, else use string
$filter = null;
if (preg_match('/^[0-9a-fA-F]{24}$/', $idParam)) {
    try {
        $filter = ['_id' => new MongoDB\BSON\ObjectId($idParam)];
    } catch (Exception $e) {
        $filter = ['_id' => $idParam];
    }
} else {
    // fallback: match by string or numeric _id
    $filter = ['_id' => $idParam];
}

$document = $collection->findOne($filter);
if (!$document) {
    $notFound = true;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Document in <?= htmlspecialchars($collectionName) ?></title>
  <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px} pre{background:#f8f8f8;padding:12px}</style>
</head>
<body>
  <a href="view_collection.php?name=<?= urlencode($collectionName) ?>">← Back to collection</a>
  <h1>Document from <?= htmlspecialchars($collectionName) ?></h1>

  <?php if (!empty($notFound)): ?>
    <p><strong>Document not found.</strong></p>
  <?php else: ?>
    <pre><?php echo htmlspecialchars(json_encode($document, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)); ?></pre>
  <?php endif; ?>
</body>
</html>
