<?php
include "koneksi.php";

$pesan = "";

// proses hapus barang
if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];

    // cek dulu apakah barang sudah dipakai di transaksi_detail
    $cek = mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi_detail WHERE barang_id = $id");
    $dipakai = mysqli_fetch_row($cek)[0];

    if ($dipakai > 0) {
        $pesan = "Barang tidak bisa dihapus karena masih digunakan pada $dipakai transaksi.";
    } else {
        mysqli_query($koneksi, "DELETE FROM barang WHERE id = $id");
        $pesan = "Barang berhasil dihapus.";
    }
}

// ambil data barang beserta nama supplier
$barang = mysqli_query($koneksi, "SELECT b.*, s.nama AS supplier FROM barang b
                                  JOIN supplier s ON s.id = b.supplier_id ORDER BY b.id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #999; padding: 5px 10px; }
        th { background: #eee; }
        form { margin: 0; }
        .pesan { background: #fff3c4; padding: 8px 12px; display: inline-block; }
    </style>
</head>
<body>
    <p><a href="transaksi.php">Transaksi</a> | <a href="barang.php">Barang</a></p>

    <h2>Data Barang</h2>

    <?php if ($pesan) { ?>
        <p class="pesan"><?= e($pesan) ?></p>
    <?php } ?>

    <table>
        <tr>
            <th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th>Supplier</th><th>Aksi</th>
        </tr>
        <?php while ($b = mysqli_fetch_assoc($barang)) { ?>
            <tr>
                <td><?= e($b['kode_barang']) ?></td>
                <td><?= e($b['nama_barang']) ?></td>
                <td><?= rp($b['harga']) ?></td>
                <td><?= $b['stok'] ?></td>
                <td><?= e($b['supplier']) ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Hapus barang ini?')">
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                        <button name="hapus">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
