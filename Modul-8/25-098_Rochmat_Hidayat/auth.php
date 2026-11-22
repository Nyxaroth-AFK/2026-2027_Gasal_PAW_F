<?php
require_once 'koneksi.php';

// Wajib dipanggil pada seluruh halaman internal (selain login.php)
function cek_login() {
    if (empty($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
        header("Location: login.php?pesan=belum_login");
        exit;
    }
}

// Wajib dipanggil pada halaman login.php agar user yang sudah login tidak bisa mengaksesnya
function cek_belum_login() {
    if (!empty($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
        header("Location: index.php");
        exit;
    }
}

// Wajib dipanggil pada seluruh halaman Data Master (khusus Level 1 / Owner)
function cek_owner() {
    cek_login();
    if ((int)($_SESSION['level'] ?? 0) !== 1) {
        header("Location: index.php?pesan=akses_ditolak");
        exit;
    }
}
