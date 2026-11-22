<?php
require_once 'auth.php';
cek_belum_login();

$error = "";
$notif = [
    'belum_login' => ['warning', 'Silakan login terlebih dahulu untuk mengakses sistem.'],
    'logout'      => ['success', 'Anda telah berhasil logout.'],
];
$pesan_get = $notif[$_GET['pesan'] ?? ''] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, nama, level FROM user WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        // Mendukung password_hash() maupun plain/md5 jika data diinput manual lewat phpMyAdmin
        $valid = false;
        if ($user) {
            if (password_verify($password, $user['password']) || $password === $user['password'] || md5($password) === $user['password']) {
                $valid = true;
            }
        }

        if ($valid) {
            session_regenerate_id(true); // Mencegah session fixation
            $_SESSION['is_login'] = true;
            $_SESSION['user_id']  = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama']     = $user['nama'];
            $_SESSION['level']    = (int)$user['level'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white text-center py-3">
          <h4 class="mb-0 fw-bold">Sistem Penjualan</h4>
          <small>Silakan Login ke Akun Anda</small>
        </div>
        <div class="card-body p-4">
          <?php if ($pesan_get): ?>
            <div class="alert alert-<?= $pesan_get[0] ?> py-2"><?= e($pesan_get[1]) ?></div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= e($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label fw-semibold">Username</label>
              <input type="text" id="username" name="username" class="form-control"
                     value="<?= e($_POST['username'] ?? '') ?>" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" id="password" name="password" class="form-control"
                     placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
