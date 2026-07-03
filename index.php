<?php
require 'config/config.php';
require 'auth/auth.php';

// Simple router based on query param `p`
$page = $_GET['p'] ?? 'home';

function view($name) {
    include __DIR__ . '/views/' . $name . '.php';
}

// Basic public home / login
if ($page === 'home') {
    view('home');
    exit;
}

// Other routes would require login
require_login();

if ($page === 'dashboard') {
    view('dashboard');
} else {
    view('404');
}
