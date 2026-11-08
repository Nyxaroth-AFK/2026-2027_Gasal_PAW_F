<?php
include "koneksi.php";

$pesan = "";

// tambah transaksi baru (data master)
if (isset($_POST['simpan_transaksi'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $pel_id  = mysqli_real_escape_string($koneksi, $_POST['pelanggan_id']);
    $ket     = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    mysqli_query($koneksi, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id)
                            VALUES ('$tanggal', '$ket', 0, '$pel_id')");

    // langsung buka detail dari transaksi yang baru dibuat
    header("Location: transaksi.php?id=" . mysqli_insert_id($koneksi));
    exit;
}

// transaksi yang sedang dipilih (dari link "Lihat")
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$master = null;
if ($id > 0) {
    $q = mysqli_query($koneksi, "SELECT t.*, p.nama AS pelanggan FROM transaksi t
                                 JOIN pelanggan p ON p.id = t.pelanggan_id WHERE t.id = $id");
    $master = mysqli_fetch_assoc($q);
}

// simpan detail transaksi
if ($master && isset($_POST['simpan_detail'])) {
    $barang_id = (int)$_POST['barang_id'];
    $qty = (int)$_POST['qty'];

    // cek apakah barang ini sudah ada di transaksi yang sama
    $cek = mysqli_query($koneksi, "SELECT COUNT(*) FROM transaksi_detail
                                   WHERE transaksi_id = $id AND barang_id = $barang_id");
    $sudah_ada = mysqli_fetch_row($cek)[0];

    if ($qty < 1) {
        $pesan = "Qty minimal 1.";
    } elseif ($sudah_ada > 0) {
        $pesan = "Barang tersebut sudah ada di transaksi ini.";
    } else {
        // harga diambil dari tabel barang
        mysqli_query($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty)
                                SELECT $id, id, harga, $qty FROM barang WHERE id = $barang_id");

        // hitung ulang total dari seluruh detail transaksi ini, lalu simpan ke tabel transaksi
        mysqli_query($koneksi, "UPDATE transaksi SET total =
                                (SELECT COALESCE(SUM(harga * qty), 0) FROM transaksi_detail WHERE transaksi_id = $id)
                                WHERE id = $id");

        header("Location: transaksi.php?id=$id");
        exit;
    }
}

// data untuk ditampilkan
$daftar    = mysqli_query($koneksi, "SELECT t.*, p.nama AS pelanggan FROM transaksi t
                                     JOIN pelanggan p ON p.id = t.pelanggan_id ORDER BY t.id DESC");
$pelanggan = mysqli_query($koneksi, "SELECT id, nama FROM pelanggan ORDER BY nama");

if ($master) {
    // isi dropdown: hanya barang yang belum ada di detail transaksi ini
    $sisa = mysqli_query($koneksi, "SELECT id, nama_barang, harga FROM barang
                                    WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = $id)
                                    ORDER BY nama_barang");
    $detail = mysqli_query($koneksi, "SELECT d.*, b.nama_barang FROM transaksi_detail d
                                      JOIN barang b ON b.id = d.barang_id WHERE d.transaksi_id = $id");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #999; padding: 5px 10px; }
        th { background: #eee; }
        .dipilih { background: #fff3c4; }
        .pesan { background: #fff3c4; padding: 8px 12px; display: inline-block; }
    </style>
</head>
<body>
    <p><a href="transaksi.php">Transaksi</a> | <a href="barang.php">Barang</a></p>

    <h2>Transaksi (Master)</h2>

    <form method="post">
        Tanggal <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required>
        Pelanggan
        <select name="pelanggan_id">
            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <option value="<?= e($p['id']) ?>"><?= e($p['nama']) ?></option>
            <?php } ?>
        </select>
        Keterangan <input type="text" name="keterangan">
        <button name="simpan_transaksi">Tambah Transaksi</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th><th>Total</th><th>Detail</th>
        </tr>
        <?php while ($t = mysqli_fetch_assoc($daftar)) { ?>
            <tr <?= $t['id'] == $id ? 'class="dipilih"' : '' ?>>
                <td><?= $t['id'] ?></td>
                <td><?= e($t['waktu_transaksi']) ?></td>
                <td><?= e($t['pelanggan']) ?></td>
                <td><?= e($t['keterangan']) ?></td>
                <td><?= rp($t['total']) ?></td>
                <td><a href="?id=<?= $t['id'] ?>">Lihat</a></td>
            </tr>
        <?php } ?>
    </table>

    <?php if ($master) { ?>
        <h2>Detail Transaksi #<?= $id ?> (<?= e($master['pelanggan']) ?>)</h2>

        <table>
            <tr><th>Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
            <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
                <tr>
                    <td><?= e($d['nama_barang']) ?></td>
                    <td><?= rp($d['harga']) ?></td>
                    <td><?= $d['qty'] ?></td>
                    <td><?= rp($d['harga'] * $d['qty']) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="3">Total</th>
                <th><?= rp($master['total']) ?></th>
            </tr>
        </table>

        <?php if ($pesan) { ?>
            <p class="pesan"><?= e($pesan) ?></p>
        <?php } ?>

        <?php if (mysqli_num_rows($sisa) > 0) { ?>
            <form method="post">
                Barang
                <select name="barang_id">
                    <?php while ($b = mysqli_fetch_assoc($sisa)) { ?>
                        <option value="<?= $b['id'] ?>"><?= e($b['nama_barang']) ?> - <?= rp($b['harga']) ?></option>
                    <?php } ?>
                </select>
                Qty <input type="number" name="qty" value="1" min="1" required>
                <button name="simpan_detail">Tambah Barang</button>
            </form>
        <?php } else { ?>
            <p>Semua barang sudah dimasukkan ke transaksi ini.</p>
        <?php } ?>
    <?php } elseif ($id > 0) { ?>
        <p>Transaksi tidak ditemukan.</p>
    <?php } ?>
</body>
</html>
