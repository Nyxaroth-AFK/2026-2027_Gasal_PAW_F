<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';

        if ($name === '' || !is_numeric($price) || (float)$price < 0 || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) {
            http_response_code(422);
            exit('Input tidak valid.');
        }

        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $stmt->execute([':name' => $name, ':price' => (float)$price, ':stock' => (int)$stock]);

        header('Location: index.php', true, 303);
        exit;

    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';

        if (filter_var($id, FILTER_VALIDATE_INT) === false || (int)$id <= 0) {
            http_response_code(422);
            exit('ID tidak valid.');
        }

        $check = $pdo->prepare('SELECT 1 FROM products WHERE id = :id');
        $check->execute([':id' => (int)$id]);
        if (!$check->fetch()) {
            http_response_code(422);
            exit('Produk tidak ditemukan.');
        }

        if ($name === '' || !is_numeric($price) || (float)$price < 0 || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) {
            http_response_code(422);
            exit('Input tidak valid.');
        }

        $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $stmt->execute([':id' => (int)$id, ':name' => $name, ':price' => (float)$price, ':stock' => (int)$stock]);

        header('Location: index.php', true, 303);
        exit;

    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if (filter_var($id, FILTER_VALIDATE_INT) === false || (int)$id <= 0) {
            http_response_code(422);
            exit('ID tidak valid.');
        }

        $check = $pdo->prepare('SELECT 1 FROM products WHERE id = :id');
        $check->execute([':id' => (int)$id]);
        if (!$check->fetch()) {
            http_response_code(422);
            exit('Produk tidak ditemukan.');
        }

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);

        header('Location: index.php', true, 303);
        exit;

    } else {
        http_response_code(400);
        exit('Aksi tidak valid.');
    }
}

$products = $pdo->query('SELECT * FROM products ORDER BY id')->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Manajemen Produk</title><link rel="stylesheet" href="style.css"></head><body>
<h1>Manajemen Produk</h1>
<form method="post" action="index.php">
<input type="hidden" name="action" value="create">
<input name="name" placeholder="Nama produk" required>
<input name="price" type="number" min="0" step="0.01" placeholder="Harga" required>
<input name="stock" type="number" min="0" placeholder="Stok" required>
<button>Tambah produk</button></form>
<table><thead><tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($products as $product): ?>
<tr>
    <td><?= esc($product['id']) ?></td>
    <td><?= esc($product['name']) ?></td>
    <td><?= esc($product['price']) ?></td>
    <td><?= esc($product['stock']) ?></td>
    <td>
        <form method="post" action="index.php" style="display:inline;">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= esc($product['id']) ?>">
            <input name="name" value="<?= esc($product['name']) ?>" required>
            <input name="price" type="number" min="0" step="0.01" value="<?= esc($product['price']) ?>" required>
            <input name="stock" type="number" min="0" value="<?= esc($product['stock']) ?>" required>
            <button type="submit">Ubah</button>
        </form>
        <form method="post" action="index.php" style="display:inline;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= esc($product['id']) ?>">
            <button type="submit">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody></table><script src="app.js"></script></body></html>