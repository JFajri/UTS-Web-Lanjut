<?php
// public/detail.php (updated: show klasifikasi)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$q = $mysqli->query("SELECT s.*, h.nama_habitat, h.lokasi, h.iklim, k.kingdom, k.filum, k.kelas, k.`ordo`, k.famili, k.genus FROM spesies s LEFT JOIN habitat h ON s.habitat_id = h.id LEFT JOIN klasifikasi k ON s.klasifikasi_id = k.id WHERE s.id = $id LIMIT 1");
if (!$q || $q->num_rows === 0) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Spesies tidak ditemukan.</div></div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}
$s = $q->fetch_assoc();

// observasi untuk spesies ini
$obs = $mysqli->query("SELECT o.*, p.nama as peneliti_nama FROM observasi o LEFT JOIN peneliti p ON o.peneliti_id = p.id WHERE o.spesies_id = $id ORDER BY tanggal DESC");
?>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-8">
      <h3><?=esc($s['nama_umum'])?> <small class="text-muted"><em><?=esc($s['nama_latin'])?></em></small></h3>
      <?php if (!empty($s['foto'])): ?>
        <img src="/sibio/public/img/spesies/<?=esc($s['foto'])?>" class="img-fluid mb-3" style="max-height:350px;object-fit:cover;">
      <?php endif; ?>
      <p><?=nl2br(esc($s['deskripsi']))?></p>
      <h5>Habitat</h5>
      <p><strong><?=esc($s['nama_habitat'])?></strong><br>
      Lokasi: <?=esc($s['lokasi'])?> — Iklim: <?=esc($s['iklim'])?><br>
      </p>

      <h5>Klasifikasi</h5>
      <?php if (!empty($s['kingdom']) || !empty($s['filum'])): ?>
        <ul>
          <li>Kingdom: <?=esc($s['kingdom'])?></li>
          <li>Filum: <?=esc($s['filum'])?></li>
          <li>Kelas: <?=esc($s['kelas'])?></li>
          <li>Ordo: <?=esc($s['ordo'])?></li>
          <li>Famili: <?=esc($s['famili'])?></li>
          <li>Genus: <?=esc($s['genus'])?></li>
        </ul>
      <?php else: ?>
        <div class="alert alert-secondary">Klasifikasi belum diisi.</div>
      <?php endif; ?>

      <h5>Observasi</h5>
      <?php if ($obs->num_rows === 0): ?>
        <div class="alert alert-info">Belum ada observasi tercatat.</div>
      <?php else: ?>
        <ul class="list-group">
          <?php while ($o = $obs->fetch_assoc()): ?>
            <li class="list-group-item">
              <strong><?=esc($o['tanggal'])?></strong> — <?=esc($o['peneliti_nama'])?>
              <div><?=nl2br(esc($o['catatan']))?></div>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>Info ringkas</h5>
          <p>Kingdom: <?=esc($s['kingdom'] ?: '—')?></p>
          <p>Habitat: <?=esc($s['nama_habitat'] ?: '—')?></p>
          <p>Kelas: <?=esc($s['kelas'] ?: '—')?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
