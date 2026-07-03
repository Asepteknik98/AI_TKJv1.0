<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /AI_TKJ/index.php');
        exit;
    }
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function logout() {
    session_destroy();
}
