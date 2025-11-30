<?php
// admin/dashboard.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="row">
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Spesies</h5>
        <?php $r = $mysqli->query("SELECT COUNT(*) as c FROM spesies")->fetch_assoc(); ?>
        <h2><?=esc($r['c'])?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Habitat</h5>
        <?php $r = $mysqli->query("SELECT COUNT(*) as c FROM habitat")->fetch_assoc(); ?>
        <h2><?=esc($r['c'])?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Peneliti</h5>
        <?php $r = $mysqli->query("SELECT COUNT(*) as c FROM peneliti")->fetch_assoc(); ?>
        <h2><?=esc($r['c'])?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h5>Total Observasi</h5>
        <?php $r = $mysqli->query("SELECT COUNT(*) as c FROM observasi")->fetch_assoc(); ?>
        <h2><?=esc($r['c'])?></h2>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h4>Statistik: Jumlah Spesies per Habitat</h4>
    <table class="table table-striped">
      <thead><tr><th>Habitat</th><th>Jumlah Spesies</th></tr></thead>
      <tbody>
        <?php
        $q = $mysqli->query("SELECT h.nama_habitat, COUNT(s.id) as jumlah FROM habitat h LEFT JOIN spesies s ON s.habitat_id = h.id GROUP BY h.id ORDER BY jumlah DESC");
        while ($row = $q->fetch_assoc()): ?>
          <tr><td><?=esc($row['nama_habitat'])?></td><td><?=esc($row['jumlah'])?></td></tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
