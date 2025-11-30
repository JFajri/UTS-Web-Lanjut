<?php
// admin/spesies.php (updated: includes klasifikasi selection)
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$action = $_GET['action'] ?? 'list';

// fetch habitats & klasifikasi for select
$habitats = $mysqli->query("SELECT * FROM habitat ORDER BY nama_habitat");
$klas = $mysqli->query("SELECT * FROM klasifikasi ORDER BY kingdom, filum, kelas");

// Handle actions
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_latin = $mysqli->real_escape_string($_POST['nama_latin']);
    $nama_umum = $mysqli->real_escape_string($_POST['nama_umum']);
    $kingdom = $mysqli->real_escape_string($_POST['kingdom']);
    $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
    $habitat_id = intval($_POST['habitat_id']);
    $klasifikasi_id = intval($_POST['klasifikasi_id']);
    $foto = upload_image($_FILES['foto']);
    $mysqli->query("INSERT INTO spesies (nama_latin,nama_umum,kingdom,deskripsi,habitat_id,klasifikasi_id,foto) VALUES ('$nama_latin','$nama_umum','$kingdom','$deskripsi',$habitat_id,$klasifikasi_id,".($foto? "'$foto'":"NULL").")");
    flash_set('Spesies berhasil ditambahkan.');
    header('Location: /sibio/admin/spesies.php');
    exit;
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Hapus foto lama
    $r = $mysqli->query("SELECT foto FROM spesies WHERE id=$id")->fetch_assoc();
    if ($r && !empty($r['foto'])) {
        @unlink(__DIR__ . '/../public/img/spesies/' . $r['foto']);
    }
    $mysqli->query("DELETE FROM spesies WHERE id=$id");
    flash_set('Spesies dihapus.');
    header('Location: /sibio/admin/spesies.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_GET['id']);
    $row = $mysqli->query("SELECT * FROM spesies WHERE id=$id")->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_latin = $mysqli->real_escape_string($_POST['nama_latin']);
        $nama_umum = $mysqli->real_escape_string($_POST['nama_umum']);
        $kingdom = $mysqli->real_escape_string($_POST['kingdom']);
        $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
        $habitat_id = intval($_POST['habitat_id']);
        $klasifikasi_id = intval($_POST['klasifikasi_id']);
        $foto = $row['foto'];
        $newfoto = upload_image($_FILES['foto']);
        if ($newfoto) {
            if (!empty($foto)) @unlink(__DIR__ . '/../public/img/spesies/' . $foto);
            $foto = $newfoto;
        }
        $mysqli->query("UPDATE spesies SET nama_latin='$nama_latin', nama_umum='$nama_umum', kingdom='$kingdom', deskripsi='$deskripsi', habitat_id=$habitat_id, klasifikasi_id=$klasifikasi_id, foto=".($foto? "'$foto'":"NULL")." WHERE id=$id");
        flash_set('Spesies diperbarui.');
        header('Location: /sibio/admin/spesies.php');
        exit;
    }
}

// list with search & sort
$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$sort = in_array($_GET['sort'] ?? '', ['nama_umum','nama_latin','kingdom']) ? $_GET['sort'] : 'nama_umum';
$where = $search ? "WHERE s.nama_umum LIKE '%$search%' OR s.nama_latin LIKE '%$search%'" : "";
$sql = "SELECT s.*, h.nama_habitat, k.kingdom as k_kingdom, k.filum as k_filum, k.kelas as k_kelas, k.genus as k_genus FROM spesies s LEFT JOIN habitat h ON s.habitat_id = h.id LEFT JOIN klasifikasi k ON s.klasifikasi_id = k.id $where ORDER BY s.$sort $order";
$list = $mysqli->query($sql);

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="d-flex justify-content-between align-items-center">
    <h3>Manajemen Spesies</h3>
    <a href="?action=create" class="btn btn-success">Tambah Spesies</a>
  </div>
  <?php if ($action === 'create'): ?>
    <div class="card mt-3 p-3">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3"><label>Nama Umum</label><input name="nama_umum" class="form-control" required></div>
        <div class="mb-3"><label>Nama Latin</label><input name="nama_latin" class="form-control" required></div>
        <div class="mb-3"><label>Kingdom</label><input name="kingdom" class="form-control"></div>

        <div class="mb-3 row">
          <div class="col-md-6">
            <label>Habitat</label>
            <select name="habitat_id" class="form-select">
              <?php
              $habitats = $mysqli->query("SELECT * FROM habitat ORDER BY nama_habitat");
              while ($h = $habitats->fetch_assoc()): ?>
                <option value="<?=esc($h['id'])?>"><?=esc($h['nama_habitat'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Klasifikasi</label>
            <select name="klasifikasi_id" class="form-select">
              <option value="0">-- Pilih / Kosongkan --</option>
              <?php
              $klasList = $mysqli->query("SELECT id, kingdom, filum, kelas, genus FROM klasifikasi ORDER BY kingdom");
              while ($k = $klasList->fetch_assoc()):
              ?>
                <option value="<?=esc($k['id'])?>"><?=esc($k['kingdom'].' / '.$k['filum'].' / '.$k['kelas'].' / '.$k['genus'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
        <div class="mb-3"><label>Foto</label><input type="file" name="foto" class="form-control"></div>
        <button class="btn btn-primary">Simpan</button>
        <a href="/sibio/admin/spesies.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>

  <?php elseif ($action === 'edit' && isset($row)): ?>
    <div class="card mt-3 p-3">
      <form method="post" enctype="multipart/form-data">
        <div class="mb-3"><label>Nama Umum</label><input name="nama_umum" class="form-control" value="<?=esc($row['nama_umum'])?>" required></div>
        <div class="mb-3"><label>Nama Latin</label><input name="nama_latin" class="form-control" value="<?=esc($row['nama_latin'])?>" required></div>
        <div class="mb-3"><label>Kingdom</label><input name="kingdom" class="form-control" value="<?=esc($row['kingdom'])?>"></div>

        <div class="mb-3 row">
          <div class="col-md-6">
            <label>Habitat</label>
            <select name="habitat_id" class="form-select">
              <?php
              $habitats = $mysqli->query("SELECT * FROM habitat ORDER BY nama_habitat");
              while ($h = $habitats->fetch_assoc()): ?>
                <option value="<?=esc($h['id'])?>" <?=($h['id']==$row['habitat_id'])?'selected':''?>><?=esc($h['nama_habitat'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Klasifikasi</label>
            <select name="klasifikasi_id" class="form-select">
              <option value="0">-- Pilih / Kosongkan --</option>
              <?php
              $klasList = $mysqli->query("SELECT id, kingdom, filum, kelas, genus FROM klasifikasi ORDER BY kingdom");
              while ($k = $klasList->fetch_assoc()):
              ?>
                <option value="<?=esc($k['id'])?>" <?=($k['id']==$row['klasifikasi_id'])?'selected':''?>><?=esc($k['kingdom'].' / '.$k['filum'].' / '.$k['kelas'].' / '.$k['genus'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"><?=esc($row['deskripsi'])?></textarea></div>
        <div class="mb-3"><label>Foto (ubah jika perlu)</label><input type="file" name="foto" class="form-control"></div>
        <button class="btn btn-primary">Update</button>
        <a href="/sibio/admin/spesies.php" class="btn btn-secondary">Batal</a>
      </form>
    </div>

  <?php else: ?>
    <div class="mt-3">
      <form class="row g-2 mb-2">
        <div class="col-md-4"><input name="search" value="<?=esc($search)?>" class="form-control" placeholder="Cari..."></div>
        <div class="col-auto">
          <select name="sort" class="form-select">
            <option value="nama_umum" <?= $sort=='nama_umum'?'selected':'' ?>>Nama Umum</option>
            <option value="nama_latin" <?= $sort=='nama_latin'?'selected':'' ?>>Nama Latin</option>
            <option value="kingdom" <?= $sort=='kingdom'?'selected':'' ?>>Kingdom</option>
          </select>
        </div>
        <div class="col-auto">
          <select name="order" class="form-select">
            <option value="asc" <?= $order=='ASC'?'selected':'' ?>>Asc</option>
            <option value="desc" <?= $order=='DESC'?'selected':'' ?>>Desc</option>
          </select>
        </div>
        <div class="col-auto"><button class="btn btn-secondary">Apply</button></div>
      </form>

      <table class="table table-striped">
        <thead><tr><th>#</th><th>Nama</th><th>Latin</th><th>Habitat</th><th>Klasifikasi</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $i=1; while ($r = $list->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= esc($r['nama_umum']) ?></td>
            <td><?= esc($r['nama_latin']) ?></td>
            <td><?= esc($r['nama_habitat']) ?></td>
            <td><?= esc(($r['k_kingdom'] ? $r['k_kingdom'].' / '.$r['k_filum'].' / '.$r['k_kelas'].' / '.$r['k_genus'] : '-')) ?></td>
            <td>
              <a href="/sibio/admin/spesies.php?action=edit&id=<?=esc($r['id'])?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="/sibio/admin/spesies.php?action=delete&id=<?=esc($r['id'])?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
