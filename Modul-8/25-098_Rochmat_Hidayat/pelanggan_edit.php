<?php
require_once 'auth.php';
cek_owner();

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($koneksi, "UPDATE pelanggan SET nama = ?, jenis_kelamin = ?, telp = ?, alamat = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $_POST['nama'], $_POST['jenis_kelamin'], $_POST['telp'], $_POST['alamat'], $id);
    mysqli_stmt_execute($stmt);
    header("Location: pelanggan.php?pesan=update");
    exit;
}

$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id = $id"));
if (!$row) {
    header("Location: pelanggan.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Data Master Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Edit Data Master Pelanggan</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama</label>
      <input type="text" name="nama" class="form-control" maxlength="100" required value="<?= e($row['nama']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Jenis Kelamin</label>
      <select name="jenis_kelamin" class="form-select" required>
        <option value="L" <?= $row['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
        <option value="P" <?= $row['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Telp</label>
      <input type="text" name="telp" class="form-control" maxlength="15" required value="<?= e($row['telp']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <input type="text" name="alamat" class="form-control" required value="<?= e($row['alamat']) ?>">
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="pelanggan.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
