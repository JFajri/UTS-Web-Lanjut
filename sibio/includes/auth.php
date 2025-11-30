<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

// Use this include on admin pages to require login
if (!is_logged_in()) {
    header('Location: /sibio/public/login.php');
    exit;
}
