<?php
require_once 'auth.php';
cek_owner();

$id = (int)($_GET['id'] ?? 0);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nama     = trim($_POST['nama']);
    $alamat   = trim($_POST['alamat']);
    $hp       = trim($_POST['hp']);
    $level    = (int)$_POST['level'];
    $pass_baru = $_POST['password'] ?? '';

    try {
        if ($pass_baru !== '') {
            $hash = password_hash($pass_baru, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($koneksi, "UPDATE user SET username = ?, password = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssssii", $username, $hash, $nama, $alamat, $hp, $level, $id);
        } else {
            $stmt = mysqli_prepare($koneksi, "UPDATE user SET username = ?, nama = ?, alamat = ?, hp = ?, level = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssssii", $username, $nama, $alamat, $hp, $level, $id);
        }
        mysqli_stmt_execute($stmt);
        header("Location: user.php?pesan=update");
        exit;
    } catch (mysqli_sql_exception $e) {
        $error = "Username sudah digunakan oleh user lain.";
    }
}

$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM user WHERE id = $id"));
if (!$row) {
    header("Location: user.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Data Master User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
  <h3 class="text-primary border-bottom pb-2 mb-3">Edit Data Master User</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Username</label>
      <input type="text" name="username" class="form-control" maxlength="50" required value="<?= e($row['username']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
      <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" maxlength="100" required value="<?= e($row['nama']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2"><?= e($row['alamat']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">No. HP</label>
      <input type="text" name="hp" class="form-control" maxlength="20" value="<?= e($row['hp']) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Level User</label>
      <select name="level" class="form-select" required>
        <option value="1" <?= (int)$row['level'] === 1 ? 'selected' : '' ?>>1 - Owner</option>
        <option value="2" <?= (int)$row['level'] === 2 ? 'selected' : '' ?>>2 - Kasir</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="user.php" class="btn btn-danger">Batal</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
