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
  <title>Profil - <?= htmlspecialchars($user['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/AI_TKJ/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container py-4">
  <h3>Profil Pengguna</h3>
  <div class="card">
    <div class="card-body">
      <p><strong>Nama:</strong> <?= htmlspecialchars($user['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
      <a href="/AI_TKJ/views/change_password_form.php" class="btn btn-outline-primary">Ubah Password</a>
    </div>
  </div>
</div>
</body>
</html>