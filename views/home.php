<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Assistant TKJ - SMK Jaya Buana</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/AI_TKJ/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title">AI Assistant TKJ</h3>
            <p class="text-muted">SMK Jaya Buana — Sistem bantu belajar untuk Jurusan TKJ</p>

            <form method="post" action="/AI_TKJ/auth/login.php">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="d-grid">
                <button class="btn btn-primary">Login</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>