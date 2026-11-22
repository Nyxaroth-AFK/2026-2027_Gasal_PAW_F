<?php
require_once 'auth.php';
cek_login();

$error = "";
$pelanggan = mysqli_query($koneksi, "SELECT id, nama FROM pelanggan ORDER BY nama");
$barang    = mysqli_query($koneksi, "SELECT id, kode_barang, nama_barang, harga, stok FROM barang WHERE stok > 0 ORDER BY nama_barang");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $waktu        = $_POST['waktu_transaksi'];
    $pelanggan_id = (int)$_POST['pelanggan_id'];
    $barang_id    = (int)$_POST['barang_id'];
    $qty          = (int)$_POST['qty'];
    $keterangan   = trim($_POST['keterangan']);
    $user_id      = (int)$_SESSION['user_id'];

    $brg = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga, stok FROM barang WHERE id = $barang_id"));

    if (!$brg || $qty <= 0 || $qty > (int)$brg['stok']) {
        $error = "Jumlah (qty) tidak valid atau melebihi stok tersedia.";
    } else {
        $harga = (int)$brg['harga'];
        $total = $harga * $qty;

        mysqli_begin_transaction($koneksi);
        try {
            $stmt1 = mysqli_prepare($koneksi, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id, user_id) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt1, "ssiii", $waktu, $keterangan, $total, $pelanggan_id, $user_id);
            mysqli_stmt_execute($stmt1);
            $transaksi_id = mysqli_insert_id($koneksi);

            $stmt2 = mysqli_prepare($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "iiii", $transaksi_id, $barang_id, $harga, $qty);
            mysqli_stmt_execute($stmt2);

            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $qty WHERE id = $barang_id");

            mysqli_commit($koneksi);
            header("Location: transaksi.php?pesan=sukses");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $error = "Terjadi kesalahan saat menyimpan transaksi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Transaksi Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Input Transaksi Penjualan</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Tanggal Transaksi</label>
      <input type="date" name="waktu_transaksi" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Pelanggan</label>
      <select name="pelanggan_id" class="form-select" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php while ($p = mysqli_fetch_assoc($pelanggan)): ?>
          <option value="<?= (int)$p['id'] ?>"><?= e($p['nama']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Barang</label>
      <select name="barang_id" class="form-select" required>
        <option value="">-- Pilih Barang --</option>
        <?php while ($b = mysqli_fetch_assoc($barang)): ?>
          <option value="<?= (int)$b['id'] ?>">
            <?= e($b['kode_barang']) ?> - <?= e($b['nama_barang']) ?> (<?= rp($b['harga']) ?> | Stok: <?= (int)$b['stok'] ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Jumlah (Qty)</label>
      <input type="number" name="qty" class="form-control" min="1" value="1" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Keterangan</label>
      <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan transaksi"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
    <a href="transaksi.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
