CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    hp VARCHAR(20),
    level TINYINT(1) NOT NULL COMMENT '1 = Owner, 2 = Kasir'
);

CREATE TABLE IF NOT EXISTS supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(15) NOT NULL,
    alamat TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    supplier_id INT NOT NULL,
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

CREATE TABLE IF NOT EXISTS pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL DEFAULT 'L',
    telp VARCHAR(15) NOT NULL,
    alamat TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT,
    total INT NOT NULL DEFAULT 0,
    pelanggan_id INT NOT NULL,
    user_id INT NULL,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS transaksi_detail (
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    harga INT NOT NULL,
    qty INT NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);

-- Akun default untuk pengujian (Username: owner & kasir | Password: password)
INSERT IGNORE INTO user (id, username, password, nama, alamat, hp, level) VALUES
(1, 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso (Owner)', 'Jl. Merdeka No. 1', '081234567890', 1),
(2, 'kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Aminah (Kasir)', 'Jl. Sudirman No. 10', '089876543210', 2);

-- Data awal opsional agar langsung bisa diuji
INSERT IGNORE INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Makmur', '08123456789', 'Jl. Raya Industri No. 12');

INSERT IGNORE INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Beras Premium 5kg', 75000, 50, 1),
(2, 'BRG002', 'Minyak Goreng 2L', 36000, 40, 1);

INSERT IGNORE INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
(1, 'Andi Pratama', 'L', '085612345678', 'Jl. Melati No. 5');
