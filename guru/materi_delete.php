<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

ensure_default_user();
$user = current_user();
if ($user['role'] !== 'guru' && $user['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'akses']); exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { echo json_encode(['success'=>false,'error'=>'invalid']); exit; }

$pdo = Database::getConnection();
$q = $pdo->prepare('SELECT pdf_path,image_path FROM materi WHERE id = ?'); $q->execute([$id]); $row = $q->fetch();
if ($row) {
    if ($row['pdf_path'] && file_exists(__DIR__ . '/../' . $row['pdf_path'])) unlink(__DIR__ . '/../' . $row['pdf_path']);
    if ($row['image_path'] && file_exists(__DIR__ . '/../' . $row['image_path'])) unlink(__DIR__ . '/../' . $row['image_path']);
}
$del = $pdo->prepare('DELETE FROM materi WHERE id = ?')->execute([$id]);
if ($del) echo json_encode(['success'=>true]); else echo json_encode(['success'=>false,'error'=>'db']);
