<?php
// includes/functions.php
// Helper umum

if (session_status() === PHP_SESSION_NONE) session_start();

function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /sibio/public/login.php');
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: /sibio/public/index.php');
        exit;
    }
}

// Simple flash messages
function flash_set($msg) {
    $_SESSION['flash'] = $msg;
}
function flash_get() {
    if (isset($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return null;
}

// Handling file upload for species image
function upload_image($file) {
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg','image/png','image/gif'];
    if (!in_array($file['type'], $allowed)) return null;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('sp_', true) . '.' . $ext;
    $dest_dir = __DIR__ . '/../public/img/spesies/';
    if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
    $dest = $dest_dir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $name;
    }
    return null;
}
