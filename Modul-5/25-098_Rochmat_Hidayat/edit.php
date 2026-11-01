<?php
include 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0); // di-cast ke int biar aman dari injection

// kalau form disubmit, update datanya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?');
    $stmt->bind_param('sssi', $_POST['nama'], $_POST['telp'], $_POST['alamat'], $id);
    $stmt->execute();
    header('Location: index.php?pesan=update');
    exit;
}

// ambil data lama buat ditampilin di form
$row = $db->query("SELECT * FROM supplier WHERE id = $id")->fetch_assoc();
if (!$row) { // id nggak ketemu, balik ke daftar
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Data Master Supplier</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css">
</head>
<body>
<div class="container">
  <h2 class="text-primary">Edit Data Master Supplier</h2>
  <hr>
  <!-- tanpa action, jadi submit ke url yang sama (id ikut kebawa) -->
  <form method="post" class="form-horizontal">
    <div class="form-group">
      <label class="col-sm-4 control-label">Nama</label>
      <div class="col-sm-5">
        <input type="text" name="nama" class="form-control" maxlength="100" required
               value="<?= htmlspecialchars($row['nama']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Telp</label>
      <div class="col-sm-3">
        <input type="text" name="telp" class="form-control" maxlength="12" required
               value="<?= htmlspecialchars($row['telp']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Alamat</label>
      <div class="col-sm-5">
        <input type="text" name="alamat" class="form-control" required
               value="<?= htmlspecialchars($row['alamat']) ?>">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-5">
        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-danger">Batal</a>
      </div>
    </div>
  </form>
</div>
</body>
</html>
