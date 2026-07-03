<?php
require 'config/config.php';
require 'auth/auth.php';

// Simple router based on query param `p`
$page = $_GET['p'] ?? 'home';

function view($name) {
    include __DIR__ . '/views/' . $name . '.php';
}

// Default to dashboard (no login required)
if ($page === 'home' || $page === 'dashboard') {
    view('dashboard');
    exit;
}

// For other pages, attempt to load the view if exists
$path = __DIR__ . '/views/' . $page . '.php';
if (file_exists($path)) {
    view($page);
} else {
    view('404');
}
