<?php
include 'koneksi.php';

// kalau form disubmit, simpan ke database terus balik ke daftar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $_POST['nama'], $_POST['telp'], $_POST['alamat']);
    $stmt->execute();
    header('Location: index.php?pesan=tambah');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Data Master Supplier</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">
  <h2 class="text-primary">Tambah Data Master Supplier Baru</h2>
  <hr>
  <form method="post" class="form-horizontal">
    <div class="form-group">
      <label class="col-sm-4 control-label">Nama</label>
      <div class="col-sm-5">
        <input type="text" name="nama" class="form-control" placeholder="Nama" maxlength="100" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Telp</label>
      <div class="col-sm-3">
        <input type="text" name="telp" class="form-control" placeholder="telp" maxlength="12" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Alamat</label>
      <div class="col-sm-5">
        <input type="text" name="alamat" class="form-control" placeholder="alamat" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-5">
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-danger">Batal</a>
      </div>
    </div>
  </form>
</div>
</body>
</html>
