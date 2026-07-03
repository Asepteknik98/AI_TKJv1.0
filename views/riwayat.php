<?php
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

require_login();
$user = current_user();
$pdo = Database::getConnection();

// Admin can see all riwayat, others see their own
if ($user['role'] === 'admin') {
    $stmt = $pdo->query('SELECT c.*, u.name FROM chat_ai c LEFT JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC LIMIT 200');
    $rows = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare('SELECT * FROM chat_ai WHERE user_id = ? ORDER BY created_at DESC LIMIT 200');
    $stmt->execute([$user['id']]);
    $rows = $stmt->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Riwayat AI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>Riwayat AI</h3>
  <div class="table-responsive">
    <table class="table table-sm">
      <thead>
        <tr><th>Tanggal</th><th>Pengguna</th><th>Pertanyaan</th><th>Jawaban</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['created_at'])?></td>
            <td><?=htmlspecialchars($r['user_id'] ? $r['user_id'] : 'System')?></td>
            <td><?=htmlspecialchars($r['question'])?></td>
            <td><?=nl2br(htmlspecialchars($r['answer']))?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>