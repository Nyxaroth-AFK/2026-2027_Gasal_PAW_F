<?php
require_once 'auth.php';
cek_owner();

$notif = [
    'tambah'  => ['success', 'Data supplier berhasil ditambahkan.'],
    'update'  => ['success', 'Data supplier berhasil diupdate.'],
    'hapus'   => ['success', 'Data supplier berhasil dihapus.'],
    'dipakai' => ['danger',  'Supplier tidak bisa dihapus karena masih dipakai di data barang.'],
];
$pesan = $notif[$_GET['pesan'] ?? ''] ?? null;

$data = $db->query('SELECT * FROM supplier ORDER BY id');
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Master Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <h3 class="text-primary mb-0">Data Master Supplier</h3>
    <a href="supplier_tambah.php" class="btn btn-success">Tambah Data</a>
  </div>

  <?php if ($pesan): ?>
    <div class="alert alert-<?= $pesan[0] ?>"><?= e($pesan[1]) ?></div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-info">
        <tr>
          <th style="width: 60px;">No</th>
          <th>Nama</th>
          <th>Telp</th>
          <th>Alamat</th>
          <th style="width: 150px;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $data->fetch_assoc()): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= e($row['nama']) ?></td>
          <td><?= e($row['telp']) ?></td>
          <td><?= e($row['alamat']) ?></td>
          <td>
            <a href="supplier_edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <form action="supplier_hapus.php" method="post" class="d-inline"
                  onsubmit="return confirm('Anda yakin akan menghapus supplier ini?')">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
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
