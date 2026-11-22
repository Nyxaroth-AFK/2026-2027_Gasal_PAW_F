<?php
require_once 'auth.php';
cek_login();

$level = (int)$_SESSION['level'];
$jml_barang    = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM barang"))[0] ?? 0;
$jml_supplier  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM supplier"))[0] ?? 0;
$jml_pelanggan = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM pelanggan"))[0] ?? 0;
$jml_transaksi = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi"))[0] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Sistem Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container">
  <?php if (($_GET['pesan'] ?? '') === 'akses_ditolak'): ?>
    <div class="alert alert-danger">
      Akses ditolak! Halaman Data Master hanya dapat diakses oleh user dengan level <strong>Owner</strong>.
    </div>
  <?php endif; ?>

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
      <h3 class="text-primary fw-bold">Selamat Datang, <?= e($_SESSION['nama']) ?>!</h3>
      <p class="text-muted mb-0">
        Anda login sebagai <strong><?= $level === 1 ? 'Owner (Level 1 - Akses Penuh)' : 'Kasir (Level 2 - Transaksi & Laporan)' ?></strong>.
      </p>
    </div>
  </div>

  <div class="row g-3">
    <?php if ($level === 1): ?>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Data Barang</h6>
          <h3 class="fw-bold text-primary"><?= (int)$jml_barang ?></h3>
          <a href="barang.php" class="btn btn-sm btn-outline-primary mt-2">Kelola Barang</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Supplier</h6>
          <h3 class="fw-bold text-success"><?= (int)$jml_supplier ?></h3>
          <a href="supplier.php" class="btn btn-sm btn-outline-success mt-2">Kelola Supplier</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Pelanggan</h6>
          <h3 class="fw-bold text-info"><?= (int)$jml_pelanggan ?></h3>
          <a href="pelanggan.php" class="btn btn-sm btn-outline-info mt-2">Kelola Pelanggan</a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="<?= $level === 1 ? 'col-md-3' : 'col-md-6' ?>">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Transaksi</h6>
          <h3 class="fw-bold text-warning"><?= (int)$jml_transaksi ?></h3>
          <a href="transaksi.php" class="btn btn-sm btn-outline-warning mt-2">Buka Transaksi</a>
          <a href="report_penjualan.php" class="btn btn-sm btn-outline-secondary mt-2">Lihat Laporan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
