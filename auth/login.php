<?php
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/auth.php';
use App\\Lib\\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /AI_TKJ/index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

$logLine = date('Y-m-d H:i:s') . " | LOGIN ATTEMPT | email={$email} ";

if ($user) {
    $ok = password_verify($password, $user['password_hash']);
    $logLine .= "| user_id={$user['id']} | password_verify=" . ($ok ? 'true' : 'false') . "\n";
    file_put_contents(__DIR__ . '/../logs/login_debug.log', $logLine, FILE_APPEND | LOCK_EX);

    if ($ok) {
        // set minimal session user
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header('Location: /AI_TKJ/index.php?p=dashboard');
        exit;
    }
} else {
    $logLine .= "| user_not_found\n";
    file_put_contents(__DIR__ . '/../logs/login_debug.log', $logLine, FILE_APPEND | LOCK_EX);
}

// failed login
header('Location: /AI_TKJ/index.php?error=invalid_credentials');
exit;
