<?php
// admin/users.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = in_array($_POST['role'], ['admin','user']) ? $_POST['role'] : 'user';
    $mysqli->query("INSERT INTO users (username,password,role) VALUES ('$username','$password','$role')");
    flash_set('User ditambahkan.');
    header('Location: /sibio/admin/users.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM users WHERE id=$id");
    flash_set('User dihapus.');
    header('Location: /sibio/admin/users.php');
    exit;
}

$list = $mysqli->query("SELECT id,username,role FROM users ORDER BY username");
include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen User</h3>
    <a href="?action=create" class="btn btn-success">Tambah User</a>
  </div>

  <?php if ($action === 'create'): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
      <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control" required></div>
      <div class="mb-3"><label>Role</label>
        <select name="role" class="form-select">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button class="btn btn-primary">Simpan</button>
    </form>
  <?php else: ?>
    <table class="table table-striped mt-3">
      <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?=esc($r['username'])?></td>
            <td><?=esc($r['role'])?></td>
            <td>
              <?php if ($r['username'] !== $_SESSION['username']): ?>
                <a href="?action=delete&id=<?=esc($r['id'])?>" onclick="return confirm('Hapus user?')" class="btn btn-sm btn-danger">Hapus</a>
              <?php else: ?>
                <span class="text-muted">Ini Anda</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
