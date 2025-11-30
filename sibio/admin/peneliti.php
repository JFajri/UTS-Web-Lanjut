<?php
// admin/peneliti.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $mysqli->real_escape_string($_POST['nama']);
    $institusi = $mysqli->real_escape_string($_POST['institusi']);
    $kontak = $mysqli->real_escape_string($_POST['kontak']);
    $mysqli->query("INSERT INTO peneliti (nama, institusi, kontak) VALUES ('$nama','$institusi','$kontak')");
    flash_set('Peneliti ditambahkan.');
    header('Location: /sibio/admin/peneliti.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM peneliti WHERE id=$id");
    flash_set('Peneliti dihapus.');
    header('Location: /sibio/admin/peneliti.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id']);
    $row = $mysqli->query("SELECT * FROM peneliti WHERE id=$id")->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = $mysqli->real_escape_string($_POST['nama']);
        $institusi = $mysqli->real_escape_string($_POST['institusi']);
        $kontak = $mysqli->real_escape_string($_POST['kontak']);
        $mysqli->query("UPDATE peneliti SET nama='$nama', institusi='$institusi', kontak='$kontak' WHERE id=$id");
        flash_set('Peneliti diperbarui.');
        header('Location: /sibio/admin/peneliti.php');
        exit;
    }
}

$list = $mysqli->query("SELECT * FROM peneliti ORDER BY nama");

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen Peneliti</h3>
    <a href="?action=create" class="btn btn-success">Tambah Peneliti</a>
  </div>

  <?php if ($action === 'create'): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="mb-3"><label>Nama</label><input name="nama" class="form-control" required></div>
      <div class="mb-3"><label>Institusi</label><input name="institusi" class="form-control"></div>
      <div class="mb-3"><label>Kontak</label><input name="kontak" class="form-control"></div>
      <button class="btn btn-primary">Simpan</button>
    </form>
  <?php elseif ($action === 'edit' && isset($row)): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="mb-3"><label>Nama</label><input name="nama" class="form-control" value="<?=esc($row['nama'])?>" required></div>
      <div class="mb-3"><label>Institusi</label><input name="institusi" class="form-control" value="<?=esc($row['institusi'])?>"></div>
      <div class="mb-3"><label>Kontak</label><input name="kontak" class="form-control" value="<?=esc($row['kontak'])?>"></div>
      <button class="btn btn-primary">Update</button>
    </form>
  <?php else: ?>
    <table class="table table-striped mt-3">
      <thead><tr><th>#</th><th>Nama</th><th>Institusi</th><th>Kontak</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?=esc($r['nama'])?></td>
            <td><?=esc($r['institusi'])?></td>
            <td><?=esc($r['kontak'])?></td>
            <td>
              <a href="?action=edit&id=<?=esc($r['id'])?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="?action=delete&id=<?=esc($r['id'])?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
