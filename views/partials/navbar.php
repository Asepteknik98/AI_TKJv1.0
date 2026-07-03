<nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="/AI_TKJ/index.php?p=dashboard">AI Assistant TKJ</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
        <li class="nav-item me-3 d-none d-md-block">
          <form class="d-flex" role="search" action="/AI_TKJ/index.php?p=ai_chat" method="get">
            <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Tanya AI..." aria-label="Search">
            <button class="btn btn-sm btn-outline-primary" type="submit">Cari</button>
          </form>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-user-circle"></i> Profil
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="/AI_TKJ/index.php?p=profile">Lihat Profil</a></li>
            <li><a class="dropdown-item" href="/AI_TKJ/index.php?p=change_password_form">Ubah Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/AI_TKJ/auth/logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>