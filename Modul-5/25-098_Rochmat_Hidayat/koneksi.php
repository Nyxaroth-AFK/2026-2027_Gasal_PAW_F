<?php
// error mysqli dijadiin exception biar bisa ditangkap pakai try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// koneksi ke database penjualan (default XAMPP: user root, password kosong)
$db = new mysqli('localhost', 'root', '', 'penjualan');
$db->set_charset('utf8mb4');
