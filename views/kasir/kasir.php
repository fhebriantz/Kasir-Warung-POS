<?php
require_once __DIR__ . '/../../controllers/BarangController.php';

$totalBarang = count(getAllBarang($pdo));

$stmtTransaksi = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(total_harga),0) as omzet FROM transaksi");
$statTransaksi = $stmtTransaksi->fetch();

$stmtHariIni = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(total_harga),0) as omzet FROM transaksi WHERE DATE(tanggal) = DATE('now','localtime')");
$statHariIni = $stmtHariIni->fetch();

$stmtStokRendah = $pdo->query("SELECT * FROM barang WHERE stok <= 5 ORDER BY stok ASC LIMIT 10");
$stokRendah = $stmtStokRendah->fetchAll();
?>

<h4 class="mb-3"><i class="bi bi-speedometer2"></i> Dashboard</h4>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Total Barang</h6>
                        <h3 class="mb-0"><?= $totalBarang ?></h3>
                    </div>
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Transaksi Hari Ini</h6>
                        <h3 class="mb-0"><?= $statHariIni['total'] ?></h3>
                    </div>
                    <i class="bi bi-cart-check" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Omzet Hari Ini</h6>
                        <h5 class="mb-0">Rp <?= number_format($statHariIni['omzet'], 0, ',', '.') ?></h5>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Total Transaksi</h6>
                        <h3 class="mb-0"><?= $statTransaksi['total'] ?></h3>
                    </div>
                    <i class="bi bi-receipt" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <a href="index.php?page=kasir" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-calculator text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-2 text-dark">Mulai Transaksi</h5>
                    <p class="text-muted mb-0">Buka halaman kasir untuk memulai penjualan</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 mb-3">
        <a href="index.php?page=barang" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-seam text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-2 text-dark">Kelola Barang</h5>
                    <p class="text-muted mb-0">Tambah, edit, atau hapus data barang</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Stok Rendah -->
<?php if (!empty($stokRendah)): ?>
<div class="card shadow-sm border-danger mb-4">
    <div class="card-header bg-danger text-white">
        <i class="bi bi-exclamation-triangle"></i> Peringatan Stok Rendah
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Barang</th>
                    <th class="text-center">Stok</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stokRendah as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['nama']) ?></td>
                    <td class="text-center"><span class="badge bg-danger"><?= $b['stok'] ?></span></td>
                    <td><?= ucfirst($b['satuan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
