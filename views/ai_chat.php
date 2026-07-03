<?php
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../lib/AI.php';
use App\Config\Config;
use App\Lib\Database;
use App\Lib\AI;

require_login();
$user = current_user();
$pdo = Database::getConnection();

$openaiAvailable = (bool) Config::getOpenAIKey();
$mode = $openaiAvailable ? 'openai' : 'local';
$q = trim($_GET['q'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = trim($_POST['question'] ?? '');
    $mode = $_POST['ai_mode'] ?? $mode;
    if ($q !== '') {
        try {
            $res = AI::chat($q, $mode);
            $userId = isset($user['id']) && $user['id'] > 0 ? $user['id'] : null;
            $stmt = $pdo->prepare('INSERT INTO chat_ai (user_id, role_user, question, answer, raw_response) VALUES (?,?,?,?,?)');
            $stmt->execute([$userId, $user['role'], $q, $res['answer'], json_encode($res['raw'])]);
            $saved = true;
            $answer = $res['answer'];
            $usedMode = $res['mode'] ?? $mode;
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
    <div class="row align-items-center mb-3">
      <div class="col-md-6 mb-2 mb-md-0">
        <label class="form-label">Mode AI</label>
        <select name="ai_mode" class="form-select">
          <option value="local" <?= ($mode === 'local' || ($usedMode ?? '') === 'local') ? 'selected' : '' ?>>AI Gratis (Local)</option>
          <option value="openai" <?= ($mode === 'openai' || ($usedMode ?? '') === 'openai') ? 'selected' : '' ?> <?= $openaiAvailable ? '' : 'disabled' ?>>OpenAI API<?= $openaiAvailable ? '' : ' (Tidak tersedia)' ?></option>
        </select>
      </div>
      <div class="col-md-6 text-md-end">
        <button class="btn btn-primary">Kirim</button>
      </div>
    </div>
  </form>

  <?php if (!empty($answer)): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h6>Jawaban AI</h6>
        <span class="badge bg-secondary mb-2"><?= htmlspecialchars(ucfirst($usedMode ?? $mode)) ?></span>
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