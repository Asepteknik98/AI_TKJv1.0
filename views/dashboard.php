<?php
<?php
require_once __DIR__ . '/../lib/Database.php';
use App\\Lib\\Database;
$pdo = Database::getConnection();

// simple counts (use safe queries; tables may not exist yet)
try {
    $guru = $pdo->query('SELECT COUNT(*) as c FROM guru')->fetch()['c'] ?? 0;
    $siswa = $pdo->query('SELECT COUNT(*) as c FROM siswa')->fetch()['c'] ?? 0;
    $materi = $pdo->query('SELECT COUNT(*) as c FROM materi')->fetch()['c'] ?? 0;
    $chat_ai = $pdo->query('SELECT COUNT(*) as c FROM chat_ai')->fetch()['c'] ?? 0;
} catch (\\Exception $e) {
    $guru = $siswa = $materi = $chat_ai = 0;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - AI Assistant TKJ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/AI_TKJ/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container-fluid">
  <div class="row">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      <h2>Dashboard</h2>
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-muted">Guru</h6>
              <h3 class="mb-0"><?= htmlspecialchars($guru) ?></h3>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-muted">Siswa</h6>
              <h3 class="mb-0"><?= htmlspecialchars($siswa) ?></h3>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-muted">Materi</h6>
              <h3 class="mb-0"><?= htmlspecialchars($materi) ?></h3>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="text-muted">Pertanyaan AI</h6>
              <h3 class="mb-0"><?= htmlspecialchars($chat_ai) ?></h3>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>