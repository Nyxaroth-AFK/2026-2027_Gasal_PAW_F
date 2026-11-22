<?php
require_once 'auth.php';
cek_login();

$sql = "SELECT t.*, p.nama AS nama_pelanggan, u.nama AS nama_kasir
        FROM transaksi t
        JOIN pelanggan p ON p.id = t.pelanggan_id
        LEFT JOIN user u ON u.id = t.user_id
        ORDER BY t.id DESC";
$transaksi = mysqli_query($koneksi, $sql);
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Transaksi Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <h3 class="text-primary mb-0">Data Transaksi Penjualan</h3>
    <a href="transaksi_tambah.php" class="btn btn-success">Tambah Transaksi</a>
  </div>

  <?php if (($_GET['pesan'] ?? '') === 'sukses'): ?>
    <div class="alert alert-success">Transaksi baru berhasil disimpan!</div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-info">
        <tr>
          <th style="width: 60px;">No</th>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th>Keterangan</th>
          <th>Total</th>
          <th>Petugas</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($transaksi)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= date('d M Y', strtotime($row['waktu_transaksi'])) ?></td>
          <td><?= e($row['nama_pelanggan']) ?></td>
          <td><?= e($row['keterangan']) ?></td>
          <td class="fw-bold"><?= rp($row['total']) ?></td>
          <td><?= e($row['nama_kasir'] ?? '-') ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
