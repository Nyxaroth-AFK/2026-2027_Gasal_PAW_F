<?php
// Gunakan /data/database.sqlite jika ada (server pengujian), atau database.sqlite lokal untuk localhost
$dbPath = file_exists('/data/database.sqlite') ? '/data/database.sqlite' : __DIR__ . '/database.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Otomatis buat tabel jika dijalankan di localhost
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        price REAL NOT NULL CHECK(price >= 0),
        stock INTEGER NOT NULL CHECK(stock >= 0)
    )");
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal');
}

if (!function_exists('esc')) {
    function esc($value) {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}