<?php
include 'koneksi.php';

// pesan notifikasi setelah tambah / edit / hapus
$notif = [
    'tambah'  => ['success', 'Data supplier berhasil ditambahkan.'],
    'update'  => ['success', 'Data supplier berhasil diupdate.'],
    'hapus'   => ['success', 'Data supplier berhasil dihapus.'],
    'dipakai' => ['danger',  'Supplier tidak bisa dihapus karena masih dipakai di data barang.'],
];
$pesan = $notif[$_GET['pesan'] ?? ''] ?? null;

// ambil semua data supplier
$data = $db->query('SELECT * FROM supplier ORDER BY id');
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Master Supplier</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">
  <a href="tambah.php" class="btn btn-success pull-right" style="margin-top:20px">Tambah Data</a>
  <h3 class="text-primary">Data Master Supplier</h3>
  <hr>

  <?php if ($pesan): ?>
    <div class="alert alert-<?= $pesan[0] ?>"><?= $pesan[1] ?></div>
  <?php endif; ?>

  <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
        <th>No</th><th>Nama</th><th>Telp</th><th>Alamat</th><th>Tindakan</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $data->fetch_assoc()): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['telp']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) ?></td>
        <td>
          <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-xs">Edit</a>
          <!-- popup konfirmasi muncul lewat confirm() sebelum form dikirim -->
          <form action="hapus.php" method="post" style="display:inline"
                onsubmit="return confirm('Anda yakin akan menghapus supplier ini?')">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
