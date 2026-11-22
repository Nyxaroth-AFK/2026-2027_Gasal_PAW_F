<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db_name = "penjualan";

$koneksi = mysqli_connect($host, $user, $pass, $db_name);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

// Alias $db agar seluruh query OOP dari modul Supplier tetap berjalan lancar
$db = $koneksi;

// Helper escaping HTML
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

// Helper format mata uang Rupiah
function rp($angka) {
    return "Rp " . number_format((float)$angka, 0, ",", ".");
}
