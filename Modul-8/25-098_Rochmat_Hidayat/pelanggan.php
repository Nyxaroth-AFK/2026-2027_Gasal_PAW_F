<?php
require_once 'auth.php';
cek_owner();

$pesan = "";
$tipe_pesan = "success";

if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];
    $cek = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi WHERE pelanggan_id = $id"))[0];
    if ($cek > 0) {
        $pesan = "Pelanggan tidak bisa dihapus karena memiliki riwayat $cek transaksi.";
        $tipe_pesan = "danger";
    } else {
        mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $id");
        $pesan = "Data pelanggan berhasil dihapus.";
    }
}

$notif = [
    'tambah' => ['success', 'Data pelanggan berhasil ditambahkan.'],
    'update' => ['success', 'Data pelanggan berhasil diupdate.'],
];
if (!$pesan && isset($notif[$_GET['pesan'] ?? ''])) {
    [$tipe_pesan, $pesan] = $notif[$_GET['pesan']];
}

$data = mysqli_query($koneksi, "SELECT * FROM pelanggan ORDER BY id");
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Master Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <h3 class="text-primary mb-0">Data Master Pelanggan</h3>
    <a href="pelanggan_tambah.php" class="btn btn-success">Tambah Pelanggan</a>
  </div>

  <?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= e($pesan) ?></div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-info">
        <tr>
          <th style="width: 60px;">No</th>
          <th>Nama</th>
          <th>Jenis Kelamin</th>
          <th>Telp</th>
          <th>Alamat</th>
          <th style="width: 150px;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= e($row['nama']) ?></td>
          <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
          <td><?= e($row['telp']) ?></td>
          <td><?= e($row['alamat']) ?></td>
          <td>
            <a href="pelanggan_edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <form method="post" class="d-inline" onsubmit="return confirm('Yakin hapus pelanggan ini?')">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
