<?php
// Simple teacher page to generate materi using AI and save
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../lib/AI.php';
use App\Lib\Database;
use App\Lib\AI;

require_login();
$user = current_user();
if ($user['role'] !== 'guru' && $user['role'] !== 'admin') {
    echo 'Akses ditolak.'; exit;
}
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    if ($title === '') { $err = 'Masukkan judul materi.'; }
    else {
        $prompt = "Buat materi ringkas tentang {$title} untuk siswa SMK TKJ. Sertakan pengertian, tujuan pembelajaran, poin-poin materi, dan kesimpulan.";
        try {
            $res = AI::chat($prompt);
            $content = $res['answer'];
            // save to materi
            $g = $pdo->prepare('SELECT id FROM guru WHERE user_id = ?');
            $g->execute([$user['id']]);
            $guru = $g->fetchColumn();
            $stmt = $pdo->prepare('INSERT INTO materi (guru_id,title,content) VALUES (?,?,?)');
            $stmt->execute([$guru ?: null, $title, $content]);
            $saved = true;
        } catch (Exception $e) {
            $err = $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>AI Pembuat Materi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../views/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>AI Pembuat Materi</h3>
  <?php if (!empty($err)): ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <?php if (!empty($saved)): ?><div class="alert alert-success">Materi tersimpan.</div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Judul Materi</label>
      <input type="text" name="title" class="form-control" placeholder="Mikrotik Dasar" required>
    </div>
    <div class="d-grid"><button class="btn btn-primary">Buat Materi</button></div>
  </form>

  <?php if (!empty($content)): ?>
    <h5 class="mt-4">Hasil AI</h5>
    <pre><?=htmlspecialchars($content)?></pre>
  <?php endif; ?>
</div>
</body>
</html>