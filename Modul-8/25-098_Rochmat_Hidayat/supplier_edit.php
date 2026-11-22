<?php
require_once 'auth.php';
cek_owner();

$id = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('UPDATE supplier SET nama = ?, telp = ?, alamat = ? WHERE id = ?');
    $stmt->bind_param('sssi', $_POST['nama'], $_POST['telp'], $_POST['alamat'], $id);
    $stmt->execute();
    header('Location: supplier.php?pesan=update');
    exit;
}

$row = $db->query("SELECT * FROM supplier WHERE id = $id")->fetch_assoc();
if (!$row) {
    header('Location: supplier.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Data Master Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Edit Data Master Supplier</h3>
  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama</label>
      <input type="text" name="nama" class="form-control" maxlength="100" required value="<?= e($row['nama']) ?>">
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
    <a href="supplier.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
