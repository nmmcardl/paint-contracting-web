<?php
require __DIR__ . '/../config.php';

$collectionName = isset($_GET['name']) ? (string) $_GET['name'] : '';
if ($collectionName === '') {
    header('Location: index.php');
    exit;
}

// basic pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 25;
$skip = ($page - 1) * $perPage;

$collection = $db->selectCollection($collectionName);

// find documents sorted by _id (server default) limited
$options = ['limit' => $perPage, 'skip' => $skip];
$cursor = $collection->find([], $options);
$docs = $cursor->toArray();

// count total for paging (cheap for small collections)
$total = $collection->countDocuments();
$lastPage = (int)ceil($total / $perPage);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Collection: <?= htmlspecialchars($collectionName) ?></title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;padding:20px}
    pre{background:#f5f5f5;padding:8px;border-radius:4px;overflow:auto}
    .meta{font-size:0.9em;color:#555}
    .nav{margin:12px 0}
  </style>
</head>
<body>
  <a href="index.php">← Back to collections</a>
  <h1>Collection: <?= htmlspecialchars($collectionName) ?></h1>
  <p class="meta">Showing page <?= $page ?> of <?= max(1,$lastPage) ?> — Total documents: <?= $total ?></p>

  <?php if (count($docs) === 0): ?>
    <p><em>No documents found.</em></p>
  <?php else: ?>
    <?php foreach ($docs as $doc): 
        // _id may be ObjectId or other type
        $idStr = (string)$doc['_id'];
    ?>
      <div>
        <pre><?php echo htmlspecialchars(json_encode($doc, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)); ?></pre>
        <a href="view_document.php?collection=<?= urlencode($collectionName) ?>&id=<?= urlencode($idStr) ?>">View document</a>
      </div>
      <hr>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="nav">
    <?php if ($page > 1): ?>
      <a href="?name=<?= urlencode($collectionName) ?>&page=<?= $page-1 ?>">&laquo; Prev</a>
    <?php endif; ?>
    <?php if ($page < $lastPage): ?>
      <a style="margin-left:12px" href="?name=<?= urlencode($collectionName) ?>&page=<?= $page+1 ?>">Next &raquo;</a>
    <?php endif; ?>
  </div>
</body>
</html>
