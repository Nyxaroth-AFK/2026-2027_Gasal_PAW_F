<?php
$level_user = (int)($_SESSION['level'] ?? 0);
$nama_user  = $_SESSION['nama'] ?? 'User';
$halaman    = basename($_SERVER['PHP_SELF']);

$is_master = in_array($halaman, [
    'barang.php', 'barang_tambah.php', 'barang_edit.php',
    'supplier.php', 'supplier_tambah.php', 'supplier_edit.php',
    'pelanggan.php', 'pelanggan_tambah.php', 'pelanggan_edit.php',
    'user.php', 'user_tambah.php', 'user_edit.php'
]);
$is_transaksi = in_array($halaman, ['transaksi.php', 'transaksi_tambah.php']);
$is_laporan   = in_array($halaman, ['report_penjualan.php']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4 no-print">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Sistem Penjualan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUtama">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarUtama">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Menu Home (Level 1 & Level 2) -->
        <li class="nav-item">
          <a class="nav-link <?= $halaman === 'index.php' ? 'active fw-semibold' : '' ?>" href="index.php">Home</a>
        </li>

        <!-- Menu Data Master (Khusus Level 1 / Owner) -->
        <?php if ($level_user === 1): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= $is_master ? 'active fw-semibold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
            Data Master
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="barang.php">Data Barang</a></li>
            <li><a class="dropdown-item" href="supplier.php">Data Supplier</a></li>
            <li><a class="dropdown-item" href="pelanggan.php">Data Pelanggan</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="user.php">Data User</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <!-- Menu Transaksi (Level 1 & Level 2) -->
        <li class="nav-item">
          <a class="nav-link <?= $is_transaksi ? 'active fw-semibold' : '' ?>" href="transaksi.php">Transaksi</a>
        </li>

        <!-- Menu Laporan (Level 1 & Level 2) -->
        <li class="nav-item">
          <a class="nav-link <?= $is_laporan ? 'active fw-semibold' : '' ?>" href="report_penjualan.php">Laporan</a>
        </li>
      </ul>

      <!-- Nama User & Tombol Logout -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
            <?= e($nama_user) ?>
            <span class="badge bg-light text-primary ms-1">
              <?= $level_user === 1 ? 'Owner' : 'Kasir' ?>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item text-danger fw-semibold" href="logout.php"
                 onclick="return confirm('Apakah Anda yakin ingin logout?')">
                Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
