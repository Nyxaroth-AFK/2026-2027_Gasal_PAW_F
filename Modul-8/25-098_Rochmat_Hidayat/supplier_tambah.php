<?php
require_once 'auth.php';
cek_owner();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $_POST['nama'], $_POST['telp'], $_POST['alamat']);
    $stmt->execute();
    header('Location: supplier.php?pesan=tambah');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Data Master Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Tambah Data Master Supplier Baru</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama</label>
      <input type="text" name="nama" class="form-control" placeholder="Nama" maxlength="100" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Telp</label>
      <input type="text" name="telp" class="form-control" placeholder="Telp" maxlength="15" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="supplier.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
