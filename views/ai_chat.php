<?php
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../lib/AI.php';
use App\Lib\Database;
use App\Lib\AI;

require_login();
$user = current_user();
$pdo = Database::getConnection();

$q = trim($_GET['q'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = trim($_POST['question'] ?? '');
    if ($q !== '') {
        try {
            $res = AI::chat($q);
            $stmt = $pdo->prepare('INSERT INTO chat_ai (user_id, role_user, question, answer, raw_response) VALUES (?,?,?,?,?)');
            $stmt->execute([$user['id'], $user['role'], $q, $res['answer'], json_encode($res['raw'])]);
            $saved = true;
            $answer = $res['answer'];
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$history = $pdo->prepare('SELECT * FROM chat_ai WHERE user_id = ? ORDER BY created_at DESC LIMIT 30');
$history->execute([$user['id']]);
$chats = $history->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Chat - AI Assistant TKJ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/AI_TKJ/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>AI Chat</h3>
  <form method="post" class="mb-3">
    <div class="mb-2">
      <input type="text" name="question" value="<?= htmlspecialchars($q) ?>" class="form-control" placeholder="Tanyakan sesuatu, mis: Apa itu IP Address?" required>
    </div>
    <div>
      <button class="btn btn-primary">Kirim</button>
    </div>
  </form>

  <?php if (!empty($answer)): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h6>Jawaban AI</h6>
        <p><?= nl2br(htmlspecialchars($answer)) ?></p>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <h5>Riwayat Terakhir</h5>
  <div class="list-group">
    <?php foreach ($chats as $c): ?>
      <div class="list-group-item">
        <div class="small text-muted"><?= htmlspecialchars($c['created_at']) ?> - <?= htmlspecialchars($c['role_user']) ?></div>
        <div><strong>Q:</strong> <?= htmlspecialchars($c['question']) ?></div>
        <div><strong>A:</strong> <?= nl2br(htmlspecialchars($c['answer'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
</body>
</html>