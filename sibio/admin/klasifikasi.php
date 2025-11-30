<?php
// admin/klasifikasi.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $kingdom = $mysqli->real_escape_string($_POST['kingdom']);
    $filum = $mysqli->real_escape_string($_POST['filum']);
    $kelas = $mysqli->real_escape_string($_POST['kelas']);
    $ordo = $mysqli->real_escape_string($_POST['ordo']);
    $famili = $mysqli->real_escape_string($_POST['famili']);
    $genus = $mysqli->real_escape_string($_POST['genus']);
    $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
    $mysqli->query("INSERT INTO klasifikasi (kingdom,filum,kelas,`ordo`,famili,genus,deskripsi) VALUES ('$kingdom','$filum','$kelas','$ordo','$famili','$genus','$deskripsi')");
    flash_set('Klasifikasi ditambahkan.');
    header('Location: /sibio/admin/klasifikasi.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // sebelum hapus, bisa cek jika ada spesies yang memakai klasifikasi ini
    $count = $mysqli->query("SELECT COUNT(*) as c FROM spesies WHERE klasifikasi_id=$id")->fetch_assoc();
    if ($count['c'] > 0) {
        flash_set('Tidak bisa dihapus: masih ada spesies terkait. Ubah dulu spesiesnya.');
    } else {
        $mysqli->query("DELETE FROM klasifikasi WHERE id=$id");
        flash_set('Klasifikasi dihapus.');
    }
    header('Location: /sibio/admin/klasifikasi.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id']);
    $row = $mysqli->query("SELECT * FROM klasifikasi WHERE id=$id")->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $kingdom = $mysqli->real_escape_string($_POST['kingdom']);
        $filum = $mysqli->real_escape_string($_POST['filum']);
        $kelas = $mysqli->real_escape_string($_POST['kelas']);
        $ordo = $mysqli->real_escape_string($_POST['ordo']);
        $famili = $mysqli->real_escape_string($_POST['famili']);
        $genus = $mysqli->real_escape_string($_POST['genus']);
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
        $mysqli->query("UPDATE klasifikasi SET kingdom='$kingdom', filum='$filum', kelas='$kelas', `ordo`='$ordo', famili='$famili', genus='$genus', deskripsi='$deskripsi' WHERE id=$id");
        flash_set('Klasifikasi diperbarui.');
        header('Location: /sibio/admin/klasifikasi.php');
        exit;
    }
}

$list = $mysqli->query("SELECT * FROM klasifikasi ORDER BY kingdom, filum, kelas");

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen Klasifikasi</h3>
    <a href="?action=create" class="btn btn-success">Tambah Klasifikasi</a>
  </div>

  <?php if ($action === 'create'): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="row">
        <div class="col-md-4 mb-2"><label>Kingdom</label><input name="kingdom" class="form-control"></div>
        <div class="col-md-4 mb-2"><label>Filum</label><input name="filum" class="form-control"></div>
        <div class="col-md-4 mb-2"><label>Kelas</label><input name="kelas" class="form-control"></div>
        <div class="col-md-4 mb-2"><label>Ordo</label><input name="ordo" class="form-control"></div>
        <div class="col-md-4 mb-2"><label>Famili</label><input name="famili" class="form-control"></div>
        <div class="col-md-4 mb-2"><label>Genus</label><input name="genus" class="form-control"></div>
      </div>
      <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
      <button class="btn btn-primary">Simpan</button>
    </form>

  <?php elseif ($action === 'edit' && isset($row)): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="row">
        <div class="col-md-4 mb-2"><label>Kingdom</label><input name="kingdom" class="form-control" value="<?=esc($row['kingdom'])?>"></div>
        <div class="col-md-4 mb-2"><label>Filum</label><input name="filum" class="form-control" value="<?=esc($row['filum'])?>"></div>
        <div class="col-md-4 mb-2"><label>Kelas</label><input name="kelas" class="form-control" value="<?=esc($row['kelas'])?>"></div>
        <div class="col-md-4 mb-2"><label>Ordo</label><input name="ordo" class="form-control" value="<?=esc($row['ordo'])?>"></div>
        <div class="col-md-4 mb-2"><label>Famili</label><input name="famili" class="form-control" value="<?=esc($row['famili'])?>"></div>
        <div class="col-md-4 mb-2"><label>Genus</label><input name="genus" class="form-control" value="<?=esc($row['genus'])?>"></div>
      </div>
      <div class="mb-2"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"><?=esc($row['deskripsi'])?></textarea></div>
      <button class="btn btn-primary">Update</button>
    </form>

  <?php else: ?>
    <table class="table table-striped mt-3">
      <thead><tr><th>#</th><th>Kingdom</th><th>Filum</th><th>Kelas</th><th>Genus</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?=esc($r['kingdom'])?></td>
            <td><?=esc($r['filum'])?></td>
            <td><?=esc($r['kelas'])?></td>
            <td><?=esc($r['genus'])?></td>
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
