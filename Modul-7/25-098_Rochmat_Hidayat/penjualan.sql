-- Membuat database penjualan
CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

-- 1. Tabel supplier
CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

-- 2. Tabel barang
CREATE TABLE barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    supplier_id INT,
    CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- 3. Tabel pelanggan
CREATE TABLE pelanggan (
    id VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
) ENGINE=InnoDB;

-- 4. Tabel transaksi
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT,
    total INT NOT NULL,
    pelanggan_id VARCHAR(20),
    CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 5. Tabel transaksi_detail
CREATE TABLE transaksi_detail (
    transaksi_id INT NOT NULL,
    barang_id INT NOT NULL,
    harga INT NOT NULL,
    qty INT NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id) REFERENCES barang(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 6. Tabel pembayaran
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME NOT NULL,
    total INT NOT NULL,
    metode ENUM('TUNAI', 'TRANSFER', 'EDC') NOT NULL,
    transaksi_id INT NOT NULL,
    CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabel user
CREATE TABLE user (
    id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150),
    hp VARCHAR(20),
    level TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

-- ==========================================================
-- DATA DUMMY UNTUK PENGUJIAN REPORT PENJUALAN
-- ==========================================================

INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1, 'PT Sumber Makmur', '081234567890', 'Jl. Industri No. 10, Surabaya'),
(2, 'CV Maju Jaya', '082345678901', 'Jl. Raya Darmo No. 25, Surabaya');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Buku Tulis Sinar Dunia 58 Lembar', 5000, 100, 1),
(2, 'BRG002', 'Pulpen Standard AE7', 3000, 150, 1),
(3, 'BRG003', 'Penghapus Faber-Castell', 5000, 80, 2);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Ahmad Fauzi', 'L', '081111111101', 'Jl. Melati No. 1'),
('P002', 'Siti Aminah', 'P', '081111111102', 'Jl. Mawar No. 2'),
('P003', 'Budi Santoso', 'L', '081111111103', 'Jl. Kenanga No. 3'),
('P004', 'Dewi Lestari', 'P', '081111111104', 'Jl. Anggrek No. 4'),
('P005', 'Eko Prasetyo', 'L', '081111111105', 'Jl. Dahlia No. 5'),
('P006', 'Fitri Handayani', 'P', '081111111106', 'Jl. Flamboyan No. 6'),
('P007', 'Gunawan Wibisono', 'L', '081111111107', 'Jl. Cempaka No. 7'),
('P008', 'Hani Rahmawati', 'P', '081111111108', 'Jl. Teratai No. 8'),
('P009', 'Irfan Hakim', 'L', '081111111109', 'Jl. Kamboja No. 9');

-- Data transaksi (Total 9 pelanggan unik & kumulatif Rp. 169.000 pada rentang 2023-09-01 s.d. 2023-09-09)
INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1,  '2023-09-01', 'Pembelian ATK', 13000, 'P001'),
(2,  '2023-09-01', 'Pembelian ATK', 13000, 'P002'),
(3,  '2023-09-01', 'Pembelian ATK', 13000, 'P003'),
(4,  '2023-09-02', 'Pembelian ATK', 13000, 'P004'),
(5,  '2023-09-02', 'Pembelian ATK', 13000, 'P005'),
(6,  '2023-09-02', 'Pembelian ATK', 13000, 'P006'),
(7,  '2023-09-02', 'Pembelian ATK', 13000, 'P007'),
(8,  '2023-09-03', 'Pembelian ATK', 13000, 'P008'),
(9,  '2023-09-04', 'Pembelian ATK', 13000, 'P009'),
(10, '2023-09-05', 'Pembelian ATK', 13000, 'P001'),
(11, '2023-09-05', 'Pembelian ATK', 13000, 'P002'),
(12, '2023-09-06', 'Pembelian ATK', 13000, 'P003'),
(13, '2023-09-09', 'Pembelian ATK', 13000, 'P004');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 5000, 1), (1, 2, 3000, 1), (1, 3, 5000, 1),
(2, 1, 5000, 1), (2, 2, 3000, 1), (2, 3, 5000, 1),
(3, 1, 5000, 1), (3, 2, 3000, 1), (3, 3, 5000, 1),
(4, 1, 5000, 1), (4, 2, 3000, 1), (4, 3, 5000, 1),
(5, 1, 5000, 1), (5, 2, 3000, 1), (5, 3, 5000, 1),
(6, 1, 5000, 1), (6, 2, 3000, 1), (6, 3, 5000, 1),
(7, 1, 5000, 1), (7, 2, 3000, 1), (7, 3, 5000, 1),
(8, 1, 5000, 1), (8, 2, 3000, 1), (8, 3, 5000, 1),
(9, 1, 5000, 1), (9, 2, 3000, 1), (9, 3, 5000, 1),
(10, 1, 5000, 1), (10, 2, 3000, 1), (10, 3, 5000, 1),
(11, 1, 5000, 1), (11, 2, 3000, 1), (11, 3, 5000, 1),
(12, 1, 5000, 1), (12, 2, 3000, 1), (12, 3, 5000, 1),
(13, 1, 5000, 1), (13, 2, 3000, 1), (13, 3, 5000, 1);

INSERT INTO pembayaran (transaksi_id, waktu_bayar, total, metode) VALUES
(1,  '2023-09-01 09:15:00', 13000, 'TUNAI'),
(2,  '2023-09-01 10:30:00', 13000, 'TRANSFER'),
(3,  '2023-09-01 14:00:00', 13000, 'TUNAI'),
(4,  '2023-09-02 08:45:00', 13000, 'EDC'),
(5,  '2023-09-02 11:20:00', 13000, 'TUNAI'),
(6,  '2023-09-02 13:10:00', 13000, 'TRANSFER'),
(7,  '2023-09-02 16:05:00', 13000, 'TUNAI'),
(8,  '2023-09-03 10:00:00', 13000, 'TUNAI'),
(9,  '2023-09-04 15:30:00', 13000, 'EDC'),
(10, '2023-09-05 09:50:00', 13000, 'TUNAI'),
(11, '2023-09-05 14:40:00', 13000, 'TRANSFER'),
(12, '2023-09-06 11:15:00', 13000, 'TUNAI'),
(13, '2023-09-09 13:25:00', 13000, 'TUNAI');

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin', MD5('admin123'), 'Administrator', 'Kantor Pusat', '081200000001', 1),
('kasir', MD5('kasir123'), 'Staff Kasir', 'Cabang Utama', '081200000002', 2);