<?php
// includes/navbar.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">
    <a class="navbar-brand" href="/sibio/public/index.php">SIBIO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/sibio/admin/spesies.php">Spesies</a></li>
        <li class="nav-item"><a class="nav-link" href="/sibio/admin/habitat.php">Habitat</a></li>
        <li class="nav-item"><a class="nav-link" href="/sibio/admin/klasifikasi.php">Klasifikasi</a></li>
      </ul>
      <ul class="navbar-nav">
<?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="/sibio/public/logout.php">Logout (<?=esc($_SESSION['username'])?>)</a></li>
        <?php if (is_admin()): ?>
          <li class="nav-item"><a class="nav-link" href="/sibio/admin/dashboard.php">Admin</a></li>
        <?php endif; ?>
<?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/sibio/public/login.php">Login</a></li>
<?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
