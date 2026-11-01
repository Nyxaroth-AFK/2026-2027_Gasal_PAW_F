-- Modul 5 : Database penjualan
-- Import lewat phpMyAdmin (tab Import) atau: mysql -u root < database.sql

DROP DATABASE IF EXISTS penjualan;
CREATE DATABASE penjualan CHARACTER SET utf8mb4;
USE penjualan;

-- ================= STRUKTUR TABEL =================

CREATE TABLE supplier (
  id     INT(11) NOT NULL AUTO_INCREMENT,
  nama   VARCHAR(100) NOT NULL,
  telp   VARCHAR(12) NOT NULL,
  alamat TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- id pelanggan varchar (sesuai diagram), jadi tidak bisa auto increment
CREATE TABLE pelanggan (
  id            VARCHAR(20) NOT NULL,
  nama          VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp          VARCHAR(12) NOT NULL,
  alamat        TEXT NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE barang (
  id          INT(11) NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(10) NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  harga       INT(11) NOT NULL,
  stok        INT(11) NOT NULL,
  supplier_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id)
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id              INT(11) NOT NULL AUTO_INCREMENT,
  waktu_transaksi DATE NOT NULL,
  keterangan      TEXT,
  total           INT(11) NOT NULL,
  pelanggan_id    VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
) ENGINE=InnoDB;

-- primary key gabungan (transaksi_id + barang_id)
CREATE TABLE transaksi_detail (
  transaksi_id INT(11) NOT NULL,
  barang_id    INT(11) NOT NULL,
  harga        INT(11) NOT NULL,
  qty          INT(11) NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
  CONSTRAINT fk_detail_barang    FOREIGN KEY (barang_id)    REFERENCES barang(id)
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id           INT(11) NOT NULL AUTO_INCREMENT,
  waktu_bayar  DATETIME NOT NULL,
  total        INT(11) NOT NULL,
  metode       ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user  TINYINT(2) NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(35) NOT NULL,
  nama     VARCHAR(50) NOT NULL,
  alamat   VARCHAR(150) NOT NULL,
  hp       VARCHAR(20) NOT NULL,
  level    TINYINT(1) NOT NULL,
  PRIMARY KEY (id_user)
) ENGINE=InnoDB;

-- ================= ISI DATA =================

-- supplier 1-8 dipakai di tabel barang, 9-12 belum dipakai (aman buat tes hapus)
INSERT INTO supplier (id, nama, telp, alamat) VALUES
(1,  'PT. Maju Bersama',   '08123456',     'Surabaya'),
(2,  'PT. Senang Sekali',  '081515563',    'Bangkalan'),
(3,  'PT. Segar Segar',    '0845454663',   'Surabaya'),
(4,  'CV. Sumber Rejeki',  '081234567890', 'Sidoarjo'),
(5,  'PT. Jaya Abadi',     '082111223344', 'Gresik'),
(6,  'CV. Berkah Makmur',  '081333445566', 'Malang'),
(7,  'PT. Karya Mandiri',  '085677889900', 'Pamekasan'),
(8,  'UD. Tani Subur',     '087788990011', 'Sampang'),
(9,  'PT. Sinar Terang',   '081944556677', 'Mojokerto'),
(10, 'CV. Mitra Niaga',    '082233445566', 'Lamongan'),
(11, 'UD. Cahaya Baru',    '081355667788', 'Sumenep'),
(12, 'PT. Global Sentosa', '082144556677', 'Kediri');

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Ahmad Fauzi',   'L', '081210001001', 'Bangkalan'),
('P002', 'Siti Aminah',   'P', '081210001002', 'Surabaya'),
('P003', 'Budi Santoso',  'L', '081210001003', 'Sidoarjo'),
('P004', 'Dewi Lestari',  'P', '081210001004', 'Gresik'),
('P005', 'Rizky Pratama', 'L', '081210001005', 'Sampang'),
('P006', 'Nur Hidayah',   'P', '081210001006', 'Pamekasan'),
('P007', 'Hendra Wijaya', 'L', '081210001007', 'Malang'),
('P008', 'Putri Ayu',     'P', '081210001008', 'Surabaya'),
('P009', 'Agus Salim',    'L', '081210001009', 'Sumenep'),
('P010', 'Rina Marlina',  'P', '081210001010', 'Bangkalan');

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1,  'BRG001', 'Beras Premium 5kg', 68000, 50,  1),
(2,  'BRG002', 'Minyak Goreng 2L',  36000, 80,  2),
(3,  'BRG003', 'Gula Pasir 1kg',    17000, 100, 3),
(4,  'BRG004', 'Telur Ayam 1kg',    29000, 60,  4),
(5,  'BRG005', 'Tepung Terigu 1kg', 12000, 90,  5),
(6,  'BRG006', 'Kopi Bubuk 200g',   15000, 70,  6),
(7,  'BRG007', 'Teh Celup 25s',     8000,  120, 7),
(8,  'BRG008', 'Susu UHT 1L',       19000, 75,  8),
(9,  'BRG009', 'Mie Instan 5pcs',   15000, 200, 1),
(10, 'BRG010', 'Sabun Mandi 3pcs',  12000, 85,  2);

-- total = jumlah (harga x qty) dari transaksi_detail
INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1,  '2026-10-01', 'Belanja bulanan',        102000, 'P001'),
(2,  '2026-10-03', 'Belanja harian',         60000,  'P002'),
(3,  '2026-10-05', 'Kebutuhan dapur',        70000,  'P003'),
(4,  '2026-10-08', 'Stok mie warung',        60000,  'P004'),
(5,  '2026-10-10', 'Kopi dan susu',          68000,  'P005'),
(6,  '2026-10-12', 'Beras untuk acara',      136000, 'P006'),
(7,  '2026-10-15', 'Belanja mingguan',       53000,  'P007'),
(8,  '2026-10-18', 'Minyak dan tepung',      96000,  'P008'),
(9,  '2026-10-21', 'Susu dan teh',           73000,  'P009'),
(10, '2026-10-25', 'Mie, kopi, dan telur',   74000,  'P010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1,  1,  68000, 1),
(1,  3,  17000, 2),
(2,  2,  36000, 1),
(2,  7,  8000,  3),
(3,  4,  29000, 2),
(3,  5,  12000, 1),
(4,  9,  15000, 4),
(5,  6,  15000, 2),
(5,  8,  19000, 2),
(6,  1,  68000, 2),
(7,  10, 12000, 3),
(7,  3,  17000, 1),
(8,  2,  36000, 2),
(8,  5,  12000, 2),
(9,  8,  19000, 3),
(9,  7,  8000,  2),
(10, 9,  15000, 2),
(10, 6,  15000, 1),
(10, 4,  29000, 1);

INSERT INTO pembayaran (id, waktu_bayar, total, metode, transaksi_id) VALUES
(1,  '2026-10-01 10:15:00', 102000, 'TUNAI',    1),
(2,  '2026-10-03 14:30:00', 60000,  'TRANSFER', 2),
(3,  '2026-10-05 09:45:00', 70000,  'EDC',      3),
(4,  '2026-10-08 16:20:00', 60000,  'TUNAI',    4),
(5,  '2026-10-10 11:05:00', 68000,  'TRANSFER', 5),
(6,  '2026-10-12 13:40:00', 136000, 'EDC',      6),
(7,  '2026-10-15 08:55:00', 53000,  'TUNAI',    7),
(8,  '2026-10-18 15:10:00', 96000,  'TRANSFER', 8),
(9,  '2026-10-21 17:25:00', 73000,  'EDC',      9),
(10, '2026-10-25 12:00:00', 74000,  'TUNAI',    10);

-- level: 1 = admin, 2 = kasir, 3 = gudang
-- password di-hash MD5 (hasilnya 32 karakter, muat di varchar(35))
INSERT INTO user (id_user, username, password, nama, alamat, hp, level) VALUES
(1,  'admin',   MD5('admin123'),  'Administrator',   'Bangkalan', '081200000001', 1),
(2,  'kasir1',  MD5('kasir123'),  'Fitri Handayani', 'Bangkalan', '081200000002', 2),
(3,  'kasir2',  MD5('kasir123'),  'Doni Kurniawan',  'Surabaya',  '081200000003', 2),
(4,  'kasir3',  MD5('kasir123'),  'Lia Safitri',     'Sidoarjo',  '081200000004', 2),
(5,  'kasir4',  MD5('kasir123'),  'Andi Setiawan',   'Gresik',    '081200000005', 2),
(6,  'kasir5',  MD5('kasir123'),  'Maya Anggraini',  'Sampang',   '081200000006', 2),
(7,  'kasir6',  MD5('kasir123'),  'Yoga Permana',    'Pamekasan', '081200000007', 2),
(8,  'gudang1', MD5('gudang123'), 'Sari Wulandari',  'Malang',    '081200000008', 3),
(9,  'gudang2', MD5('gudang123'), 'Bayu Nugroho',    'Sumenep',   '081200000009', 3),
(10, 'gudang3', MD5('gudang123'), 'Eka Putri',       'Bangkalan', '081200000010', 3);
