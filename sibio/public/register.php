<?php
// public/register.php - opsional, untuk user biasa
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $mysqli->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];
    $role = 'user';

    // cek username
    $q = $mysqli->query("SELECT id FROM users WHERE username = '$username' LIMIT 1");
    if ($q && $q->num_rows > 0) {
        $message = 'Username sudah dipakai';
    } else {
        $pw = password_hash($password, PASSWORD_DEFAULT);
        $mysqli->query("INSERT INTO users (username, password, role) VALUES ('$username', '$pw', '$role')");
        $_SESSION['user_id'] = $mysqli->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header('Location: index.php');
        exit;
    }
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <h4>Register</h4>
          <?php if ($message): ?><div class="alert alert-warning"><?=esc($message)?></div><?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label>Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <button class="btn btn-primary">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
