<?php
require_once 'auth.php';
cek_owner();

$pesan = "";
$tipe_pesan = "warning";

// Proses hapus barang (mempertahankan pengecekan transaksi_detail dari modul sebelumnya)
if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];

    $cek = mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = $id");
    $dipakai = mysqli_fetch_row($cek)[0];

    if ($dipakai > 0) {
        $pesan = "Barang tidak bisa dihapus karena masih digunakan pada $dipakai transaksi.";
        $tipe_pesan = "danger";
    } else {
        mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id");
        $pesan = "Barang berhasil dihapus.";
        $tipe_pesan = "success";
    }
}

$notif = [
    'tambah' => ['success', 'Data barang berhasil ditambahkan.'],
    'update' => ['success', 'Data barang berhasil diupdate.'],
];
if (!$pesan && isset($notif[$_GET['pesan'] ?? ''])) {
    [$tipe_pesan, $pesan] = $notif[$_GET['pesan']];
}

$barang = mysqli_query($koneksi, "SELECT b.*, s.nama AS supplier FROM barang b
                                  JOIN supplier s ON s.id = b.supplier_id ORDER BY b.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
        <h3 class="text-primary mb-0">Data Master Barang</h3>
        <a href="barang_tambah.php" class="btn btn-success">Tambah Barang</a>
    </div>

    <?php if ($pesan): ?>
        <div class="alert alert-<?= $tipe_pesan ?>"><?= e($pesan) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-info">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Supplier</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = mysqli_fetch_assoc($barang)): ?>
                    <tr>
                        <td><?= e($b['kode_barang']) ?></td>
                        <td><?= e($b['nama_barang']) ?></td>
                        <td><?= rp($b['harga']) ?></td>
                        <td><?= (int)$b['stok'] ?></td>
                        <td><?= e($b['supplier']) ?></td>
                        <td>
                            <a href="barang_edit.php?id=<?= (int)$b['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form method="post" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                                <button name="hapus" class="btn btn-danger btn-sm">Hapus</button>
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
