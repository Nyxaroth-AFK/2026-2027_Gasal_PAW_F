<?php
require_once 'auth.php';
cek_login();

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    die("Parameter tanggal tidak valid.");
}

$nama_file = "Rekap_Laporan_Penjualan_" . $tanggal_mulai . "_sd_" . $tanggal_selesai . ".xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$nama_file\"");
header("Pragma: no-cache");
header("Expires: 0");

$sql_harian = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
               FROM transaksi 
               WHERE waktu_transaksi BETWEEN ? AND ? 
               GROUP BY waktu_transaksi 
               ORDER BY waktu_transaksi ASC";

$stmt_harian = mysqli_prepare($koneksi, $sql_harian);
mysqli_stmt_bind_param($stmt_harian, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt_harian);
$res_harian = mysqli_stmt_get_result($stmt_harian);

$sql_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan, 
                     COALESCE(SUM(total), 0) AS total_semua 
              FROM transaksi 
              WHERE waktu_transaksi BETWEEN ? AND ?";

$stmt_total = mysqli_prepare($koneksi, $sql_total);
mysqli_stmt_bind_param($stmt_total, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt_total);
$row_total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total));

$jumlah_pelanggan  = (int) ($row_total['jml_pelanggan'] ?? 0);
$total_keseluruhan = (int) ($row_total['total_semua'] ?? 0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h3>Rekap Laporan Penjualan <?= e($tanggal_mulai) ?> sampai <?= e($tanggal_selesai) ?></h3>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #d9edf7; font-weight: bold;">
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($res_harian)): 
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color: #d9edf7; font-weight: bold;">
                <th>Jumlah Pelanggan</th>
                <th>Jumlah Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td><?= $jumlah_pelanggan ?> Orang</td>
                <td>Rp. <?= number_format($total_keseluruhan, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
<?php
mysqli_stmt_close($stmt_harian);
mysqli_stmt_close($stmt_total);
?>
