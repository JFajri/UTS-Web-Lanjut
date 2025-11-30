<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBIO - Sistem Informasi Keanekaragaman Hayati</title>
    <!-- Bootstrap via CDN (lebih mudah) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
<?php
// flash message
if (!empty($_SESSION['flash'])) {
    echo '<div class="container mt-3"><div class="alert alert-info">'.htmlspecialchars($_SESSION['flash']).'</div></div>';
    unset($_SESSION['flash']);
}
?>
