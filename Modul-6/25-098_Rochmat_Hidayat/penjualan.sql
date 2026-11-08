CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4;
USE penjualan;

-- hapus tabel lama (tabel anak dulu, baru induk)
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS transaksi_detail;
DROP TABLE IF EXISTS transaksi;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS user;

-- ===== STRUKTUR TABEL =====

CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
);

CREATE TABLE user (
  id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  alamat VARCHAR(150),
  nama VARCHAR(50),
  hp VARCHAR(20),
  level TINYINT(1)
);

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT NOT NULL,
  supplier_id INT NOT NULL,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE NOT NULL,
  keterangan TEXT,
  total INT NOT NULL DEFAULT 0,
  pelanggan_id VARCHAR(20) NOT NULL,
  FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
);

-- primary key gabungan: satu barang hanya bisa sekali dalam satu transaksi
-- ON DELETE RESTRICT: barang yang sudah dipakai di detail tidak bisa dihapus
CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id INT NOT NULL,
  harga INT NOT NULL,
  qty INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE RESTRICT
);

CREATE TABLE pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME NOT NULL,
  total INT NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
);

-- ===== DATA MASTER (masing-masing 5 data atau lebih) =====

INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Pangan', '081234560001', 'Jl. Rungkut Industri No. 5, Surabaya'),
('CV Madura Jaya', '081234560002', 'Jl. Raya Bangkalan No. 21, Bangkalan'),
('UD Berkah Tani', '081234560003', 'Jl. Pemuda No. 8, Sampang'),
('PT Aneka Boga', '081234560004', 'Jl. Ahmad Yani No. 112, Surabaya'),
('CV Makmur Sejahtera', '081234560005', 'Jl. Trunojoyo No. 3, Pamekasan');

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001', 'Budi Santoso', 'L', '081330010001', 'Jl. Telang Indah No. 7, Bangkalan'),
('PLG002', 'Siti Aisyah', 'P', '081330010002', 'Jl. Kamal Raya No. 15, Bangkalan'),
('PLG003', 'Ahmad Fauzi', 'L', '081330010003', 'Jl. Soekarno Hatta No. 40, Sampang'),
('PLG004', 'Dewi Lestari', 'P', '081330010004', 'Jl. Panglima Sudirman No. 9, Pamekasan'),
('PLG005', 'Rizky Maulana', 'L', '081330010005', 'Jl. Bhayangkara No. 22, Bangkalan');

INSERT INTO user (username, password, alamat, nama, hp, level) VALUES
('admin', MD5('admin123'), 'Jl. Raya Telang No. 1, Bangkalan', 'Administrator', '081200000001', 1),
('kasir1', MD5('kasir123'), 'Jl. Kamal Raya No. 3, Bangkalan', 'Nur Hidayah', '081200000002', 2),
('kasir2', MD5('kasir123'), 'Jl. Soekarno Hatta No. 12, Bangkalan', 'Dimas Prasetyo', '081200000003', 2),
('gudang1', MD5('gudang123'), 'Jl. Trunojoyo No. 5, Bangkalan', 'Lukman Hakim', '081200000004', 3),
('gudang2', MD5('gudang123'), 'Jl. Pemuda No. 17, Bangkalan', 'Fitria Ramadhani', '081200000005', 3);

-- BRG007 dan BRG008 sengaja belum dipakai di transaksi manapun (untuk mencoba hapus barang)
INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001', 'Beras Premium 5 kg', 68000, 50, 1),
('BRG002', 'Minyak Goreng 1 L', 18000, 80, 1),
('BRG003', 'Gula Pasir 1 kg', 16000, 100, 2),
('BRG004', 'Telur Ayam 1 kg', 30000, 40, 2),
('BRG005', 'Kopi Bubuk 200 g', 22000, 60, 3),
('BRG006', 'Teh Celup 25 pcs', 9500, 120, 3),
('BRG007', 'Mie Instan Goreng', 3500, 200, 4),
('BRG008', 'Sabun Cair 450 ml', 24000, 35, 5);

-- ===== DATA TRANSAKSI (10 transaksi) =====

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-26', 'Belanja bulanan', 0, 'PLG001'),
('2026-10-27', 'Stok warung', 0, 'PLG002'),
('2026-10-28', 'Belanja mingguan', 0, 'PLG003'),
('2026-10-29', 'Pesanan kantor', 0, 'PLG004'),
('2026-10-30', 'Belanja harian', 0, 'PLG005'),
('2026-11-01', 'Beli kopi', 0, 'PLG001'),
('2026-11-02', 'Belanja bulanan', 0, 'PLG002'),
('2026-11-04', 'Stok warung', 0, 'PLG003'),
('2026-11-05', 'Pesanan acara keluarga', 0, 'PLG004'),
('2026-11-07', 'Belanja mingguan', 0, 'PLG005');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1, 1, 68000, 1), (1, 2, 18000, 2),
(2, 3, 16000, 5), (2, 4, 30000, 2),
(3, 5, 22000, 3), (3, 6, 9500, 4),
(4, 1, 68000, 2), (4, 3, 16000, 3), (4, 6, 9500, 2),
(5, 2, 18000, 4), (5, 4, 30000, 1),
(6, 5, 22000, 2),
(7, 1, 68000, 3), (7, 2, 18000, 3), (7, 5, 22000, 1),
(8, 4, 30000, 3), (8, 6, 9500, 6),
(9, 2, 18000, 5), (9, 3, 16000, 10),
(10, 1, 68000, 1), (10, 4, 30000, 2), (10, 6, 9500, 5);

-- total transaksi dihitung dari detailnya (harga x qty)
UPDATE transaksi SET total = (
  SELECT SUM(harga * qty) FROM transaksi_detail WHERE transaksi_id = transaksi.id
);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-26 10:15:00', 104000, 'TUNAI', 1),
('2026-10-27 09:40:00', 140000, 'TRANSFER', 2),
('2026-10-28 14:05:00', 104000, 'TUNAI', 3),
('2026-10-29 11:30:00', 203000, 'EDC', 4),
('2026-10-30 16:20:00', 102000, 'TUNAI', 5),
('2026-11-01 08:55:00', 44000, 'TUNAI', 6),
('2026-11-02 13:10:00', 280000, 'TRANSFER', 7),
('2026-11-04 10:45:00', 147000, 'EDC', 8),
('2026-11-05 15:00:00', 250000, 'TRANSFER', 9),
('2026-11-07 12:25:00', 175500, 'TUNAI', 10);
