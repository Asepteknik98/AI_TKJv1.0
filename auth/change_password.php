<?php
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/auth.php';
use App\Lib\Database;

require_login();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /AI_TKJ/index.php?p=profile');
    exit;
}

$old = $_POST['old_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($new !== $confirm) {
    header('Location: /AI_TKJ/views/change_password_form.php?error=nomatch');
    exit;
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();
if (!$row || !password_verify($old, $row['password_hash'])) {
    header('Location: /AI_TKJ/views/change_password_form.php?error=wrong');
    exit;
}

$hash = password_hash($new, PASSWORD_DEFAULT);
$pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$hash, $user['id']]);

header('Location: /AI_TKJ/index.php?p=profile&msg=pass_changed');
exit;
