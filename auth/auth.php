<?php
session_start();

// Auto-login helper: if no session user exists, try to set a default admin
function ensure_default_user()
{
    if (isset($_SESSION['user'])) return;

    // try to fetch an admin user from database
    $cfgPath = __DIR__ . '/../lib/Database.php';
    if (file_exists($cfgPath)) {
        require_once $cfgPath;
        try {
            $pdo = App\Lib\Database::getConnection();
            $stmt = $pdo->query("SELECT id,name,email,role FROM users WHERE role='admin' LIMIT 1");
            $u = $stmt->fetch();
            if ($u) {
                $_SESSION['user'] = $u;
                return;
            }
        } catch (Exception $e) {
            // ignore, fallback to guest
        }
    }

    // Fallback default user
    $_SESSION['user'] = [
        'id' => 0,
        'name' => 'Administrator',
        'email' => 'admin@local',
        'role' => 'admin'
    ];
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function require_login() {
    // no-op because app is set to auto-login; keep for compatibility
    ensure_default_user();
}

function current_user() {
    ensure_default_user();
    return $_SESSION['user'] ?? null;
}

function logout() {
    session_destroy();
}
