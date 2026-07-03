<?php
require_once __DIR__ . '/../auth/auth.php';
require_once __DIR__ . '/../lib/Database.php';
use App\Lib\Database;

require_login();
$user = current_user();
if ($user['role'] !== 'guru' && $user['role'] !== 'admin') {
    echo 'Akses ditolak.'; exit;
}

$pdo = Database::getConnection();

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        // delete files
        $q = $pdo->prepare('SELECT pdf_path,image_path FROM materi WHERE id = ?');
        $q->execute([$id]);
        $row = $q->fetch();
        if ($row) {
            if ($row['pdf_path'] && file_exists(__DIR__ . '/../' . $row['pdf_path'])) unlink(__DIR__ . '/../' . $row['pdf_path']);
            if ($row['image_path'] && file_exists(__DIR__ . '/../' . $row['image_path'])) unlink(__DIR__ . '/../' . $row['image_path']);
        }
        $pdo->prepare('DELETE FROM materi WHERE id = ?')->execute([$id]);
        header('Location: /AI_TKJ/index.php?p=materi&msg=deleted'); exit;
    }

    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';

    // handle uploads
    $pdf_path = null; $image_path = null;
    if (!empty($_FILES['pdf']['name'])) {
        $f = $_FILES['pdf'];
        if ($f['error'] === 0) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $fn = 'assets/uploads/' . time() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/../' . $fn);
            $pdf_path = $fn;
        }
    }
    if (!empty($_FILES['image']['name'])) {
        $f = $_FILES['image'];
        if ($f['error'] === 0) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $fn = 'assets/uploads/' . time() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/../' . $fn);
            $image_path = $fn;
        }
    }

    if (!empty($_POST['id'])) {
        // update
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare('UPDATE materi SET title = ?, content = ?, pdf_path = COALESCE(?, pdf_path), image_path = COALESCE(?, image_path) WHERE id = ?');
        $stmt->execute([$title, $content, $pdf_path, $image_path, $id]);
        header('Location: /AI_TKJ/index.php?p=materi&msg=updated'); exit;
    } else {
        // insert
        // find guru id
        $g = $pdo->prepare('SELECT id FROM guru WHERE user_id = ?'); $g->execute([$user['id']]); $guru_id = $g->fetchColumn() ?: null;
        $stmt = $pdo->prepare('INSERT INTO materi (guru_id,title,content,pdf_path,image_path) VALUES (?,?,?,?,?)');
        $stmt->execute([$guru_id, $title, $content, $pdf_path, $image_path]);
        header('Location: /AI_TKJ/index.php?p=materi&msg=created'); exit;
    }
}

$stmt = $pdo->query('SELECT m.*, u.name as guru_name FROM materi m LEFT JOIN guru g ON m.guru_id = g.id LEFT JOIN users u ON g.user_id = u.id ORDER BY m.created_at DESC');
$materis = $stmt->fetchAll();

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kelola Materi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . '/../views/partials/navbar.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Kelola Materi</h3>
    <a href="#formAdd" class="btn btn-primary" data-bs-toggle="collapse">Tambah Materi</a>
  </div>

  <div class="collapse mb-4" id="formAdd">
    <div class="card card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3"><label class="form-label">Judul</label><input name="title" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Konten</label><textarea name="content" class="form-control" rows="6"></textarea></div>
        <div class="mb-3"><label class="form-label">PDF (opsional)</label><input type="file" name="pdf" accept="application/pdf" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Gambar (opsional)</label><input type="file" name="image" accept="image/*" class="form-control"></div>
        <div class="d-grid"><button class="btn btn-success">Simpan</button></div>
      </form>
    </div>
  </div>

  <div class="row">
    <?php foreach ($materis as $m): ?>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <?php if ($m['image_path']): ?><img src="/AI_TKJ/<?= htmlspecialchars($m['image_path']) ?>" class="card-img-top" alt=""><?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($m['title']) ?></h5>
            <p class="small-muted mb-2">Oleh: <?= htmlspecialchars($m['guru_name'] ?: 'Unknown') ?> • <?= htmlspecialchars($m['created_at']) ?></p>
            <p class="card-text text-truncate"><?= htmlspecialchars($m['content']) ?></p>
            <div class="mt-auto d-flex gap-2">
              <a class="btn btn-outline-primary btn-sm" href="/AI_TKJ/index.php?p=materi_view&id=<?= $m['id'] ?>">Baca</a>
              <?php if ($m['pdf_path']): ?><a class="btn btn-outline-secondary btn-sm" href="/AI_TKJ/<?= htmlspecialchars($m['pdf_path']) ?>" target="_blank">Unduh PDF</a><?php endif; ?>
              <form method="post" onsubmit="return confirm('Hapus materi ini?')" style="display:inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button class="btn btn-danger btn-sm">Hapus</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
