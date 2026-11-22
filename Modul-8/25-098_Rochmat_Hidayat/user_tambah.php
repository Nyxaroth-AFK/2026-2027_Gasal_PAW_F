<?php
require_once 'auth.php';
cek_owner();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama     = trim($_POST['nama']);
    $alamat   = trim($_POST['alamat']);
    $hp       = trim($_POST['hp']);
    $level    = (int)$_POST['level'];

    try {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO user (username, password, nama, alamat, hp, level) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssi", $username, $password, $nama, $alamat, $hp, $level);
        mysqli_stmt_execute($stmt);
        header("Location: user.php?pesan=tambah");
        exit;
    } catch (mysqli_sql_exception $e) {
        $error = "Username sudah terdaftar, gunakan username lain.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Data Master User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Tambah Data Master User</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Username</label>
      <input type="text" name="username" class="form-control" maxlength="50" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" maxlength="100" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">No. HP</label>
      <input type="text" name="hp" class="form-control" maxlength="20">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Level User</label>
      <select name="level" class="form-select" required>
        <option value="1">1 - Owner</option>
        <option value="2">2 - Kasir</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="user.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
