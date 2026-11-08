<?php
// koneksi ke database penjualan (sesuaikan user & password dengan MySQL di komputer)
$koneksi = mysqli_connect("localhost", "root", "", "penjualan");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

// supaya teks aman saat ditampilkan di html
function e($teks) {
    return htmlspecialchars((string)$teks);
}

// format rupiah
function rp($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}
