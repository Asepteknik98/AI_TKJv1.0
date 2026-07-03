<?php
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/auth.php';
use App\Lib\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /AI_TKJ/index.php');
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$pdo = Database::getConnection();
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
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

header('Location: /AI_TKJ/index.php?error=invalid_credentials');
exit;
