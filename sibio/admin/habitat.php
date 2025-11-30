<?php
// admin/habitat.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $mysqli->real_escape_string($_POST['nama_habitat']);
    $lokasi = $mysqli->real_escape_string($_POST['lokasi']);
    $iklim = $mysqli->real_escape_string($_POST['iklim']);
    $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
    $mysqli->query("INSERT INTO habitat (nama_habitat,lokasi,iklim,deskripsi) VALUES ('$nama','$lokasi','$iklim','$deskripsi')");
    flash_set('Habitat ditambahkan.');
    header('Location: /sibio/admin/habitat.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM habitat WHERE id=$id");
    flash_set('Habitat dihapus.');
    header('Location: /sibio/admin/habitat.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id']);
    $row = $mysqli->query("SELECT * FROM habitat WHERE id=$id")->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = $mysqli->real_escape_string($_POST['nama_habitat']);
        $lokasi = $mysqli->real_escape_string($_POST['lokasi']);
        $iklim = $mysqli->real_escape_string($_POST['iklim']);
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
        $mysqli->query("UPDATE habitat SET nama_habitat='$nama', lokasi='$lokasi', iklim='$iklim', deskripsi='$deskripsi' WHERE id=$id");
        flash_set('Habitat diperbarui.');
        header('Location: /sibio/admin/habitat.php');
        exit;
    }
}

$list = $mysqli->query("SELECT * FROM habitat ORDER BY nama_habitat");

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen Habitat</h3>
    <a href="?action=create" class="btn btn-success">Tambah Habitat</a>
  </div>

  <?php if ($action === 'create'): ?>
    <div class="card p-3 mt-3">
      <form method="post">
        <div class="mb-3"><label>Nama Habitat</label><input name="nama_habitat" class="form-control" required></div>
        <div class="mb-3"><label>Lokasi</label><input name="lokasi" class="form-control"></div>
        <div class="mb-3"><label>Iklim</label><input name="iklim" class="form-control"></div>
        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
        <button class="btn btn-primary">Simpan</button>
        <a href="/sibio/admin/habitat.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  <?php elseif ($action === 'edit' && isset($row)): ?>
    <div class="card p-3 mt-3">
      <form method="post">
        <div class="mb-3"><label>Nama Habitat</label><input name="nama_habitat" class="form-control" value="<?=esc($row['nama_habitat'])?>" required></div>
        <div class="mb-3"><label>Lokasi</label><input name="lokasi" class="form-control" value="<?=esc($row['lokasi'])?>"></div>
        <div class="mb-3"><label>Iklim</label><input name="iklim" class="form-control" value="<?=esc($row['iklim'])?>"></div>
        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"><?=esc($row['deskripsi'])?></textarea></div>
        <button class="btn btn-primary">Update</button>
      </form>
    </div>
  <?php else: ?>
    <table class="table table-striped mt-3">
      <thead><tr><th>#</th><th>Nama</th><th>Lokasi</th><th>Iklim</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?=esc($r['nama_habitat'])?></td>
            <td><?=esc($r['lokasi'])?></td>
            <td><?=esc($r['iklim'])?></td>
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
