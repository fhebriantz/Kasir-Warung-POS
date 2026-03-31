<?php
$bulan = $_GET['bulan'] ?? date('Y-m');

// Omzet per hari dalam bulan
$stmtHarian = $pdo->prepare("
    SELECT DATE(tanggal) as tgl,
           COUNT(*) as jumlah_trx,
           SUM(total_harga) as omzet,
           SUM(total_harga - (
               SELECT COALESCE(SUM(dt.jumlah * b.harga_modal), 0)
               FROM detail_transaksi dt
               LEFT JOIN barang b ON b.id = dt.barang_id
               WHERE dt.transaksi_id = transaksi.id
           )) as laba_kotor
    FROM transaksi
    WHERE strftime('%Y-%m', tanggal) = :bulan
    GROUP BY DATE(tanggal)
    ORDER BY tgl ASC
");
$stmtHarian->execute([':bulan' => $bulan]);
$dataHarian = $stmtHarian->fetchAll();

// Ringkasan bulan
$stmtRingkasan = $pdo->prepare("
    SELECT COUNT(*) as total_trx,
           COALESCE(SUM(total_harga), 0) as total_omzet,
           COALESCE(AVG(total_harga), 0) as rata_rata
    FROM transaksi
    WHERE strftime('%Y-%m', tanggal) = :bulan
");
$stmtRingkasan->execute([':bulan' => $bulan]);
$ringkasan = $stmtRingkasan->fetch();

// Barang terlaris bulan ini
$stmtTerlaris = $pdo->prepare("
    SELECT dt.nama_barang,
           SUM(dt.jumlah) as total_qty,
           SUM(dt.subtotal) as total_pendapatan
    FROM detail_transaksi dt
    JOIN transaksi t ON t.id = dt.transaksi_id
    WHERE strftime('%Y-%m', t.tanggal) = :bulan
    GROUP BY dt.barang_id
    ORDER BY total_qty DESC
    LIMIT 10
");
$stmtTerlaris->execute([':bulan' => $bulan]);
$terlaris = $stmtTerlaris->fetchAll();

// Hitung total laba kotor
$totalLaba = 0;
foreach ($dataHarian as $h) {
    $totalLaba += $h['laba_kotor'];
}

$namaBulan = DateTime::createFromFormat('Y-m', $bulan);
$labelBulan = $namaBulan ? $namaBulan->format('F Y') : $bulan;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-graph-up"></i> Laporan Bulanan</h4>
    <button class="btn btn-outline-success btn-sm" onclick="window.print()">
        <i class="bi bi-printer"></i> Cetak Laporan
    </button>
</div>

<!-- Filter Bulan -->
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row align-items-center g-2">
            <input type="hidden" name="page" value="laporan">
            <div class="col-auto">
                <label class="form-label mb-0 fw-bold">Bulan:</label>
            </div>
            <div class="col-auto">
                <input type="month" name="bulan" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($bulan) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-auto">
                <a href="index.php?page=laporan&bulan=<?= date('Y-m') ?>" class="btn btn-outline-secondary btn-sm">
                    Bulan Ini
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row mb-3">
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body py-3">
                <small>Total Transaksi</small>
                <h4 class="mb-0"><?= number_format($ringkasan['total_trx']) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body py-3">
                <small>Total Omzet</small>
                <h4 class="mb-0">Rp <?= number_format($ringkasan['total_omzet'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body py-3">
                <small>Laba Kotor</small>
                <h4 class="mb-0">Rp <?= number_format($totalLaba, 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body py-3">
                <small>Rata-rata / Transaksi</small>
                <h4 class="mb-0">Rp <?= number_format($ringkasan['rata_rata'], 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tabel Omzet Harian -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="bi bi-calendar3"></i> Omzet Harian — <?= $labelBulan ?>
            </div>
            <div class="card-body p-0">
                <?php if (empty($dataHarian)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0">Belum ada transaksi di bulan ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-center">Transaksi</th>
                                    <th class="text-end">Omzet</th>
                                    <th class="text-end">Laba Kotor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataHarian as $h): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?page=transaksi&tanggal=<?= $h['tgl'] ?>" class="text-decoration-none">
                                            <?= date('d/m/Y (D)', strtotime($h['tgl'])) ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $h['jumlah_trx'] ?></span>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($h['omzet'], 0, ',', '.') ?></td>
                                    <td class="text-end text-success">Rp <?= number_format($h['laba_kotor'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td>Total</td>
                                    <td class="text-center"><?= $ringkasan['total_trx'] ?></td>
                                    <td class="text-end">Rp <?= number_format($ringkasan['total_omzet'], 0, ',', '.') ?></td>
                                    <td class="text-end text-success">Rp <?= number_format($totalLaba, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Barang Terlaris -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="bi bi-trophy"></i> Barang Terlaris
            </div>
            <div class="card-body p-0">
                <?php if (empty($terlaris)): ?>
                    <div class="text-center text-muted py-5">
                        <p class="mb-0">Belum ada data.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">#</th>
                                    <th>Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($terlaris as $i => $t): ?>
                                <tr>
                                    <td>
                                        <?php if ($i === 0): ?>
                                            <span class="badge bg-warning text-dark">1</span>
                                        <?php elseif ($i === 1): ?>
                                            <span class="badge bg-secondary">2</span>
                                        <?php elseif ($i === 2): ?>
                                            <span class="badge bg-danger">3</span>
                                        <?php else: ?>
                                            <span class="text-muted"><?= $i + 1 ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($t['nama_barang']) ?></td>
                                    <td class="text-center"><?= $t['total_qty'] ?></td>
                                    <td class="text-end">Rp <?= number_format($t['total_pendapatan'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
