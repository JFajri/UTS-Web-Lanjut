<?php
// admin/laporan.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // export observasi to CSV filtered by date range (optional)
    $from = isset($_GET['from']) ? $mysqli->real_escape_string($_GET['from']) : null;
    $to = isset($_GET['to']) ? $mysqli->real_escape_string($_GET['to']) : null;

    $where = "1";
    if ($from) $where .= " AND tanggal >= '$from'";
    if ($to) $where .= " AND tanggal <= '$to'";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=laporan_observasi_'.date('YmdHis').'.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','Tanggal','Spesies','Peneliti','Catatan']);
    $q = $mysqli->query("SELECT o.*, s.nama_umum as nama_spesies, p.nama as nama_peneliti FROM observasi o LEFT JOIN spesies s ON s.id=o.spesies_id LEFT JOIN peneliti p ON p.id=o.peneliti_id WHERE $where ORDER BY tanggal DESC");
    while ($r = $q->fetch_assoc()) {
        fputcsv($out, [$r['id'],$r['tanggal'],$r['nama_spesies'],$r['nama_peneliti'],$r['catatan']]);
    }
    fclose($out);
    exit;
}

// For PDF: memberikan instruksi & contoh jika ingin menggunakan FPDF.
// Jika ingin generate PDF otomatis, letakkan fpdf.php di folder /lib/ dan gunakan contoh:
// require_once __DIR__ . '/../lib/fpdf.php';
// ... (generate PDF menggunakan FPDF)

include __DIR__ . '/../includes/header.php';
?>
<div class="container mt-4">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <h3>Laporan Observasi</h3>
  <form class="row g-2 mt-2">
    <div class="col-md-3"><label>Dari</label><input type="date" name="from" class="form-control"></div>
    <div class="col-md-3"><label>Sampai</label><input type="date" name="to" class="form-control"></div>
    <div class="col-auto align-self-end">
      <button formaction="?export=csv" formmethod="get" class="btn btn-success">Export CSV</button>
    </div>
  </form>

  <hr>
  <h5>PDF</h5>
  <p>Untuk export PDF otomatis, silakan download library <strong>FPDF</strong> (http://www.fpdf.org) dan letakkan <code>fpdf.php</code> di <code>/sibio/lib/</code>, lalu uncomment contoh kode di file ini untuk generate PDF.</p>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
