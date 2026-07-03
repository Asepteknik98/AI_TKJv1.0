<?php
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

$id = (int)($_GET['id'] ?? 0);
$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT m.*, u.name as guru_name FROM materi m LEFT JOIN guru g ON m.guru_id = g.id LEFT JOIN users u ON g.user_id = u.id WHERE m.id = ? LIMIT 1');
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) {
    http_response_code(404); echo 'Materi tidak ditemukan.'; exit;
}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($m['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <h2><?= htmlspecialchars($m['title']) ?></h2>
  <p class="small-muted">Oleh <?= htmlspecialchars($m['guru_name'] ?: 'Unknown') ?> • <?= htmlspecialchars($m['created_at']) ?></p>
  <?php if ($m['image_path']): ?><img src="/AI_TKJ/<?= htmlspecialchars($m['image_path']) ?>" class="img-fluid mb-3" alt=""><?php endif; ?>
  <div class="card p-3 mb-3"><div><?= nl2br(htmlspecialchars($m['content'])) ?></div></div>
  <?php if ($m['pdf_path']): ?><a class="btn btn-primary" href="/AI_TKJ/<?= htmlspecialchars($m['pdf_path']) ?>" target="_blank">Unduh Materi (PDF)</a><?php endif; ?>
</div>
</body>
</html>
