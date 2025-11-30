<?php
// public/index.php (updated snippet: join klasifikasi)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

$search = isset($_GET['search']) ? $mysqli->real_escape_string(trim($_GET['search'])) : '';
$sort = isset($_GET['sort']) ? $mysqli->real_escape_string($_GET['sort']) : 'nama_umum';
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

$where = "";
if ($search !== '') {
    $where = "WHERE s.nama_latin LIKE '%$search%' OR s.nama_umum LIKE '%$search%'";
}

$valid_sort_columns = ['nama_umum','nama_latin','kingdom'];
if (!in_array($sort, $valid_sort_columns)) $sort = 'nama_umum';

$sql = "SELECT s.*, h.nama_habitat, k.kingdom, k.filum, k.kelas FROM spesies s LEFT JOIN habitat h ON s.habitat_id = h.id LEFT JOIN klasifikasi k ON s.klasifikasi_id = k.id $where ORDER BY s.$sort $order";
$res = $mysqli->query($sql);
?>
<div class="container mt-4">
  <h2>Daftar Spesies</h2>
  <form class="row g-2 mb-3">
    <div class="col-md-6">
      <input name="search" value="<?=esc($search)?>" class="form-control" placeholder="Cari nama spesies atau latin...">
    </div>
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
    <div class="col-auto">
      <button class="btn btn-success">Filter</button>
    </div>
  </form>

  <div class="row">
    <?php while ($sp = $res->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <?php if (!empty($sp['foto'])): ?>
            <img src="/sibio/public/img/spesies/<?=esc($sp['foto'])?>" class="card-img-top" style="height:200px;object-fit:cover;">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?=esc($sp['nama_umum'])?></h5>
            <p class="card-text"><em><?=esc($sp['nama_latin'])?></em></p>
            <p class="card-text"><small>Habitat: <?=esc($sp['nama_habitat'] ?: '—')?></small></p>
            <p class="card-text"><small>Klasifikasi: <?=esc(($sp['kingdom']? $sp['kingdom'].' / '.$sp['filum'].' / '.$sp['kelas'] : '-'))?></small></p>
            <a href="/sibio/public/detail.php?id=<?=esc($sp['id'])?>" class="btn btn-sm btn-primary">Detail</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
