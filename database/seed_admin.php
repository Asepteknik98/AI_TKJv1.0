<?php
// Run this once to create an initial admin user.
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

$pdo = Database::getConnection();

$email = 'admin@smkjayabuana.local';
$password = 'admin123';
$name = 'Administrator';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin user already exists.\n";
    exit;
}

$pdo->prepare('INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,"admin")')
    ->execute([$name,$email,$hash]);

echo "Admin user created: {$email} / {$password}\n";
