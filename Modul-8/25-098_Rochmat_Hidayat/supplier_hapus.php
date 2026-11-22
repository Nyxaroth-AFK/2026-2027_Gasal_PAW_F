<?php
require_once 'auth.php';
cek_owner();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: supplier.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = $db->prepare('DELETE FROM supplier WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: supplier.php?pesan=hapus');
} catch (mysqli_sql_exception $e) {
    header('Location: supplier.php?pesan=dipakai');
}
exit;
