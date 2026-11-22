<?php
require_once 'auth.php';
cek_login();

$tanggal_mulai   = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$labels            = [];
$data_total        = [];
$rekap_harian      = [];
$jumlah_pelanggan  = 0;
$total_keseluruhan = 0;
$is_filtered       = false;

if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
    $is_filtered = true;

    $sql_harian = "SELECT waktu_transaksi AS tgl, SUM(total) AS total_harian 
                   FROM transaksi 
                   WHERE waktu_transaksi BETWEEN ? AND ? 
                   GROUP BY waktu_transaksi 
                   ORDER BY waktu_transaksi ASC";

    $stmt_harian = mysqli_prepare($koneksi, $sql_harian);
    mysqli_stmt_bind_param($stmt_harian, "ss", $tanggal_mulai, $tanggal_selesai);
    mysqli_stmt_execute($stmt_harian);
    $res_harian = mysqli_stmt_get_result($stmt_harian);

    while ($row = mysqli_fetch_assoc($res_harian)) {
        $labels[]       = $row['tgl'];
        $data_total[]   = (int) $row['total_harian'];
        $rekap_harian[] = $row;
    }
    mysqli_stmt_close($stmt_harian);

    $sql_total = "SELECT COUNT(DISTINCT pelanggan_id) AS jml_pelanggan, 
                         COALESCE(SUM(total), 0) AS total_semua 
                  FROM transaksi 
                  WHERE waktu_transaksi BETWEEN ? AND ?";

    $stmt_total = mysqli_prepare($koneksi, $sql_total);
    mysqli_stmt_bind_param($stmt_total, "ss", $tanggal_mulai, $tanggal_selesai);
    mysqli_stmt_execute($stmt_total);
    $res_total = mysqli_stmt_get_result($stmt_total);

    if ($row_total = mysqli_fetch_assoc($res_total)) {
        $jumlah_pelanggan  = (int) $row_total['jml_pelanggan'];
        $total_keseluruhan = (int) $row_total['total_semua'];
    }
    mysqli_stmt_close($stmt_total);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .table-header-custom th {
            background-color: #d9edf7 !important;
            color: #31708f !important;
        }
        .summary-card {
            max-width: 450px;
            border: 1px solid #bce8f1;
        }
        .summary-card .card-header {
            background-color: #d9edf7;
            color: #31708f;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
            }
            .table-header-custom th,
            .summary-card .card-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4 p-4 bg-white rounded shadow-sm">
    <h3 class="text-primary border-bottom pb-2 mb-3">
        Rekap Laporan Penjualan 
        <?php if ($is_filtered): ?>
            <small class="text-muted fs-5">
                <?= e($tanggal_mulai) ?> sampai <?= e($tanggal_selesai) ?>
            </small>
        <?php endif; ?>
    </h3>

    <div class="card mb-4 no-print">
        <div class="card-body bg-light">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" 
                           value="<?= e($tanggal_mulai) ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" 
                           value="<?= e($tanggal_selesai) ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success px-4">Tampilkan</button>
                    <?php if ($is_filtered): ?>
                        <a href="report_penjualan.php" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if ($is_filtered): ?>
        <div class="mb-4 no-print d-flex gap-2">
            <button type="button" onclick="window.print()" class="btn btn-warning text-white fw-bold">
                Cetak
            </button>
            <a href="export_excel.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>" 
               class="btn btn-warning text-white fw-bold">
                Excel
            </a>
        </div>

        <?php if (!empty($rekap_harian)): ?>
            <div class="mb-4" style="max-width: 750px; height: 320px;">
                <canvas id="chartPenjualan"></canvas>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-header-custom">
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 45%;">Total Pendapatan</th>
                            <th style="width: 45%;">Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($rekap_harian as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.') ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card summary-card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">Jumlah Pelanggan</div>
                        <div class="col-6">Jumlah Pendapatan</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row fw-bold text-primary fs-5">
                        <div class="col-6"><?= $jumlah_pelanggan ?> Orang</div>
                        <div class="col-6">Rp. <?= number_format($total_keseluruhan, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <script>
                const ctx = document.getElementById('chartPenjualan').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($labels) ?>,
                        datasets: [{
                            label: 'Total Pendapatan (Rp)',
                            data: <?= json_encode($data_total) ?>,
                            backgroundColor: 'rgba(217, 217, 217, 0.85)',
                            borderColor: 'rgba(160, 160, 160, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { title: { display: true, text: 'Tanggal Transaksi' } },
                            y: { beginAtZero: true, title: { display: true, text: 'Total Pendapatan (Rp)' } }
                        }
                    }
                });
            </script>
        <?php else: ?>
            <div class="alert alert-info">
                Tidak ada data transaksi pada rentang tanggal <strong><?= e($tanggal_mulai) ?></strong> sampai <strong><?= e($tanggal_selesai) ?></strong>.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-secondary no-print">
            Silakan pilih rentang <strong>Tanggal Mulai</strong> dan <strong>Tanggal Selesai</strong>, lalu klik <strong>Tampilkan</strong> untuk melihat laporan penjualan.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
