<?php
require_once __DIR__ . '/../auth/auth.php';
require_login();
$user = current_user();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ubah Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>Ubah Password</h3>
  <form method="post" action="/AI_TKJ/auth/change_password.php">
    <div class="mb-3">
      <label class="form-label">Password Lama</label>
      <input type="password" name="old_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password Baru</label>
      <input type="password" name="new_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Konfirmasi Password Baru</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <div class="d-grid">
      <button class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
</body>
</html>