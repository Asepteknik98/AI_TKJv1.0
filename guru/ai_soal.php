<?php
// Simple teacher page to generate soal using AI and save
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
    $topic = trim($_POST['topic'] ?? '');
    if ($topic === '') { $err = 'Masukkan topik.'; }
    else {
        $prompt = "Buat 5 soal pilihan ganda tentang {$topic} untuk siswa SMK. Sertakan pilihan A-E, kunci jawaban, dan pembahasan singkat masing-masing.";
        try {
            $res = AI::chat($prompt);
            $questions = $res['answer'];
            // save to soal table
            $stmt = $pdo->prepare('INSERT INTO soal (guru_id,title,questions) VALUES (?,?,?)');
            // find guru_id
            $g = $pdo->prepare('SELECT id FROM guru WHERE user_id = ?');
            $g->execute([$user['id']]);
            $guru = $g->fetchColumn();
            $stmt->execute([$guru ?: null, $topic, $questions]);
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
  <title>AI Pembuat Soal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/../views/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>AI Pembuat Soal</h3>
  <?php if (!empty($err)): ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <?php if (!empty($saved)): ?><div class="alert alert-success">Soal tersimpan.</div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Topik</label>
      <input type="text" name="topic" class="form-control" placeholder="Subnetting" required>
    </div>
    <div class="d-grid"><button class="btn btn-primary">Buat Soal</button></div>
  </form>

  <?php if (!empty($questions)): ?>
    <h5 class="mt-4">Hasil AI</h5>
    <pre><?=htmlspecialchars($questions)?></pre>
  <?php endif; ?>
</div>
</body>
</html>