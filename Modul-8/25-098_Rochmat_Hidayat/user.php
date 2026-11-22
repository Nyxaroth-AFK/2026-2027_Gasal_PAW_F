<?php
require_once 'auth.php';
cek_owner();

$pesan = "";
$tipe_pesan = "success";

if (isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];
    if ($id === (int)$_SESSION['user_id']) {
        $pesan = "Anda tidak dapat menghapus akun user yang sedang aktif digunakan login.";
        $tipe_pesan = "danger";
    } else {
        try {
            $stmt = mysqli_prepare($koneksi, "DELETE FROM user WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $pesan = "Data user berhasil dihapus.";
        } catch (mysqli_sql_exception $e) {
            $pesan = "User tidak dapat dihapus karena masih terkait dengan data transaksi.";
            $tipe_pesan = "danger";
        }
    }
}

$notif = [
    'tambah' => ['success', 'Data user berhasil ditambahkan.'],
    'update' => ['success', 'Data user berhasil diupdate.'],
];
if (!$pesan && isset($notif[$_GET['pesan'] ?? ''])) {
    [$tipe_pesan, $pesan] = $notif[$_GET['pesan']];
}

$data = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id");
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Master User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container bg-white p-4 rounded shadow-sm">
  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
    <h3 class="text-primary mb-0">Data Master User</h3>
    <a href="user_tambah.php" class="btn btn-success">Tambah User</a>
  </div>

  <?php if ($pesan): ?>
    <div class="alert alert-<?= $tipe_pesan ?>"><?= e($pesan) ?></div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-info">
        <tr>
          <th style="width: 60px;">No</th>
          <th>Username</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>No. HP</th>
          <th>Level</th>
          <th style="width: 150px;">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= e($row['username']) ?></td>
          <td><?= e($row['nama']) ?></td>
          <td><?= e($row['alamat']) ?></td>
          <td><?= e($row['hp']) ?></td>
          <td>
            <?php if ((int)$row['level'] === 1): ?>
              <span class="badge bg-primary">1 - Owner</span>
            <?php else: ?>
              <span class="badge bg-secondary">2 - Kasir</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="user_edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <form method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
