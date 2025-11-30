<?php
// public/login.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $mysqli->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];

    $q = $mysqli->query("SELECT id, username, password, role FROM users WHERE username = '$username' LIMIT 1");
    if ($q && $q->num_rows === 1) {
        $u = $q->fetch_assoc();
        if ($password === $u['password']) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['username'];
            $_SESSION['role'] = $u['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Username tidak ditemukan.';
    }
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="card-title mb-4">Login SIBIO</h4>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?=esc($error)?></div>
          <?php endif; ?>
          <form method="post" action="">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <button class="btn btn-success w-100">Login</button>
          </form>
          <hr>
          <small>Jika belum punya akun, hubungi admin atau gunakan fitur registrasi (opsional).</small>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
