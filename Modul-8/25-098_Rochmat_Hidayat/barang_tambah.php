<?php
require_once 'auth.php';
cek_owner();

$error = "";
$suppliers = mysqli_query($koneksi, "SELECT id, nama FROM supplier ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode        = trim($_POST['kode_barang']);
    $nama        = trim($_POST['nama_barang']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $supplier_id = (int)$_POST['supplier_id'];

    try {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiii", $kode, $nama, $harga, $stok, $supplier_id);
        mysqli_stmt_execute($stmt);
        header("Location: barang.php?pesan=tambah");
        exit;
    } catch (mysqli_sql_exception $e) {
        $error = "Gagal menambah barang (pastikan Kode Barang belum dipakai).";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Data Master Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Tambah Data Master Barang</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Kode Barang</label>
      <input type="text" name="kode_barang" class="form-control" maxlength="20" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Barang</label>
      <input type="text" name="nama_barang" class="form-control" maxlength="100" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Harga (Rp)</label>
      <input type="number" name="harga" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Stok</label>
      <input type="number" name="stok" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Supplier</label>
      <select name="supplier_id" class="form-select" required>
        <option value="">-- Pilih Supplier --</option>
        <?php while ($s = mysqli_fetch_assoc($suppliers)): ?>
          <option value="<?= (int)$s['id'] ?>"><?= e($s['nama']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="barang.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
