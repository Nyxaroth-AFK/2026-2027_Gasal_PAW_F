<?php
include 'koneksi.php';

// hapus cuma lewat POST (dari form di index.php)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = $db->prepare('DELETE FROM supplier WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: index.php?pesan=hapus');
} catch (mysqli_sql_exception $e) {
    // gagal biasanya karena supplier masih dipakai di tabel barang (foreign key)
    header('Location: index.php?pesan=dipakai');
}
exit;
