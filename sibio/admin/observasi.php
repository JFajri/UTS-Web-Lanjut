<?php
// admin/observasi.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

// fetch options
$sp = $mysqli->query("SELECT id, nama_umum FROM spesies ORDER BY nama_umum");
$pen = $mysqli->query("SELECT id, nama FROM peneliti ORDER BY nama");

// create
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $spesies_id = intval($_POST['spesies_id']);
    $peneliti_id = intval($_POST['peneliti_id']);
    $tanggal = $mysqli->real_escape_string($_POST['tanggal']);
    $catatan = $mysqli->real_escape_string($_POST['catatan']);
    $mysqli->query("INSERT INTO observasi (spesies_id,peneliti_id,tanggal,catatan) VALUES ($spesies_id,$peneliti_id,'$tanggal','$catatan')");
    flash_set('Observasi ditambahkan.');
    header('Location: /sibio/admin/observasi.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM observasi WHERE id=$id");
    flash_set('Observasi dihapus.');
    header('Location: /sibio/admin/observasi.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id']);
    $row = $mysqli->query("SELECT * FROM observasi WHERE id=$id")->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $spesies_id = intval($_POST['spesies_id']);
        $peneliti_id = intval($_POST['peneliti_id']);
        $tanggal = $mysqli->real_escape_string($_POST['tanggal']);
        $catatan = $mysqli->real_escape_string($_POST['catatan']);
        $mysqli->query("UPDATE observasi SET spesies_id=$spesies_id, peneliti_id=$peneliti_id, tanggal='$tanggal', catatan='$catatan' WHERE id=$id");
        flash_set('Observasi diperbarui.');
        header('Location: /sibio/admin/observasi.php');
        exit;
    }
}

// listing with join
$list = $mysqli->query("SELECT o.*, s.nama_umum as nama_spesies, p.nama as nama_peneliti FROM observasi o LEFT JOIN spesies s ON s.id=o.spesies_id LEFT JOIN peneliti p ON p.id=o.peneliti_id ORDER BY tanggal DESC");

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen Observasi</h3>
    <a href="?action=create" class="btn btn-success">Tambah Observasi</a>
  </div>

  <?php if ($action === 'create'): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="mb-3"><label>Spesies</label>
        <select name="spesies_id" class="form-select">
          <?php while ($r = $sp->fetch_assoc()): ?>
            <option value="<?=esc($r['id'])?>"><?=esc($r['nama_umum'])?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3"><label>Peneliti</label>
        <select name="peneliti_id" class="form-select">
          <?php while ($r = $pen->fetch_assoc()): ?>
            <option value="<?=esc($r['id'])?>"><?=esc($r['nama'])?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3"><label>Tanggal</label><input name="tanggal" type="date" class="form-control" required></div>
      <div class="mb-3"><label>Catatan</label><textarea name="catatan" class="form-control"></textarea></div>
      <button class="btn btn-primary">Simpan</button>
    </form>
  <?php elseif ($action === 'edit' && isset($row)): ?>
    <form method="post" class="card p-3 mt-3">
      <div class="mb-3"><label>Spesies</label>
        <select name="spesies_id" class="form-select">
          <?php $sp_sel = $mysqli->query("SELECT id,nama_umum FROM spesies ORDER BY nama_umum"); while ($r = $sp_sel->fetch_assoc()): ?>
            <option value="<?=esc($r['id'])?>" <?=($r['id']==$row['spesies_id'])?'selected':''?>><?=esc($r['nama_umum'])?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3"><label>Peneliti</label>
        <select name="peneliti_id" class="form-select">
          <?php $pen_sel = $mysqli->query("SELECT id,nama FROM peneliti ORDER BY nama"); while ($r = $pen_sel->fetch_assoc()): ?>
            <option value="<?=esc($r['id'])?>" <?=($r['id']==$row['peneliti_id'])?'selected':''?>><?=esc($r['nama'])?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3"><label>Tanggal</label><input name="tanggal" type="date" class="form-control" value="<?=esc($row['tanggal'])?>" required></div>
      <div class="mb-3"><label>Catatan</label><textarea name="catatan" class="form-control"><?=esc($row['catatan'])?></textarea></div>
      <button class="btn btn-primary">Update</button>
    </form>
  <?php else: ?>
    <table class="table table-striped mt-3">
      <thead><tr><th>#</th><th>Tanggal</th><th>Spesies</th><th>Peneliti</th><th>Catatan</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?=esc($r['tanggal'])?></td>
            <td><?=esc($r['nama_spesies'])?></td>
            <td><?=esc($r['nama_peneliti'])?></td>
            <td><?=nl2br(esc($r['catatan']))?></td>
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
