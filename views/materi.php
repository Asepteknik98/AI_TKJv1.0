<?php
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

$pdo = Database::getConnection();
$pageNum = max(1, (int)($_GET['page'] ?? 1));
$limit = 6;
$offset = ($pageNum - 1) * $limit;

$total = (int)$pdo->query('SELECT COUNT(*) FROM materi')->fetchColumn();
$stmt = $pdo->prepare('SELECT m.*, u.name as guru_name FROM materi m LEFT JOIN guru g ON m.guru_id = g.id LEFT JOIN users u ON g.user_id = u.id ORDER BY m.created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$materis = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Materi - AI Assistant TKJ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/AI_TKJ/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3>Materi</h3>
      <p class="text-muted">Kumpulan materi belajar untuk siswa TKJ.</p>
    </div>
    <a class="btn btn-outline-primary" href="/AI_TKJ/index.php?p=materi_manage">Kelola Materi</a>
  </div>

  <?php if (empty($materis)): ?>
    <div class="alert alert-secondary">Belum ada materi tersedia.</div>
  <?php endif; ?>

  <div class="row gy-3">
    <?php foreach ($materis as $m): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <?php if ($m['image_path']): ?>
            <img src="/AI_TKJ/<?= htmlspecialchars($m['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($m['title']) ?>">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($m['title']) ?></h5>
            <p class="small-muted mb-2">Oleh <?= htmlspecialchars($m['guru_name'] ?: 'Admin') ?> • <?= htmlspecialchars($m['created_at']) ?></p>
            <p class="card-text text-truncate"><?= htmlspecialchars($m['content']) ?></p>
            <div class="mt-auto pt-2">
              <a class="btn btn-primary btn-sm" href="/AI_TKJ/index.php?p=materi_view&id=<?= $m['id'] ?>">Baca</a>
              <?php if ($m['pdf_path']): ?>
                <a class="btn btn-outline-secondary btn-sm" href="/AI_TKJ/<?= htmlspecialchars($m['pdf_path']) ?>" target="_blank">Unduh PDF</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($total > $limit): ?>
    <nav aria-label="Paginasi Materi" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($page = 1; $page <= ceil($total / $limit); $page++): ?>
          <li class="page-item <?= $page === $pageNum ? 'active' : '' ?>">
            <a class="page-link" href="/AI_TKJ/index.php?p=materi&page=<?= $page ?>"><?= $page ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
