<?php
require_once __DIR__ . '/../../controllers/BarangController.php';

$stmtHariIni = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(total_harga),0) as omzet FROM transaksi WHERE DATE(tanggal) = DATE('now','localtime')");
$statHariIni = $stmtHariIni->fetch();

$stmtStokRendah = $pdo->query("SELECT * FROM barang WHERE stok <= stok_minimal ORDER BY stok ASC");
$stokRendah = $stmtStokRendah->fetchAll();

$stmtStokHabis = $pdo->query("SELECT COUNT(*) FROM barang WHERE stok = 0");
$jumlahHabis = (int) $stmtStokHabis->fetchColumn();

$stmtKeuntungan = $pdo->query("
    SELECT COALESCE(SUM(dt.subtotal - (b.harga_modal * dt.jumlah)), 0) as laba
    FROM detail_transaksi dt
    JOIN barang b ON b.id = dt.barang_id
    JOIN transaksi t ON t.id = dt.transaksi_id
    WHERE DATE(t.tanggal) = DATE('now','localtime')
");
$labaHariIni = $stmtKeuntungan ? (float) $stmtKeuntungan->fetchColumn() : 0;
?>

<?php
$isDemo = false;
$cekDemo = $pdo->query("SELECT nilai FROM pengaturan WHERE kunci = 'demo_mode'");
if ($cekDemo) {
    $row = $cekDemo->fetch();
    $isDemo = $row && $row['nilai'] === '1';
}
?>

<?php if ($isDemo): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle-fill"></i>
    <strong>Mode Demo</strong> — Data barang dan transaksi saat ini adalah data contoh.
    Untuk mulai menggunakan aplikasi dengan data asli, silakan
    <a href="index.php?page=pengaturan" class="alert-link">reset database di halaman Pengaturan</a>.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<h4 class="mb-3"><i class="bi bi-speedometer2"></i> Dashboard</h4>

<div class="row mb-4">
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
        <div class="card shadow-sm border-0 bg-success text-white">
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
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Laba Hari Ini</h6>
                        <h5 class="mb-0">Rp <?= number_format($labaHariIni, 0, ',', '.') ?></h5>
                    </div>
                    <i class="bi bi-graph-up-arrow" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm border-0 <?= ($jumlahHabis > 0 || count($stokRendah) > 0) ? 'bg-danger' : 'bg-secondary' ?> text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Stok Perlu Isi</h6>
                        <h3 class="mb-0"><?= count($stokRendah) ?> <small style="font-size: 0.5em;">(<?= $jumlahHabis ?> habis)</small></h3>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.5;"></i>
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
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-exclamation-triangle"></i> Stok Rendah & Habis</span>
        <span class="badge bg-light text-danger"><?= count($stokRendah) ?> barang</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Barang</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Minimal</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stokRendah as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['nama']) ?></td>
                    <td class="text-center">
                        <span class="badge <?= $b['stok'] == 0 ? 'bg-dark' : 'bg-danger' ?>">
                            <?= $b['stok'] == 0 ? 'Habis' : $b['stok'] ?>
                        </span>
                    </td>
                    <td class="text-center"><?= $b['stok_minimal'] ?></td>
                    <td><?= ucfirst($b['satuan']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="alert alert-success">
    <i class="bi bi-check-circle"></i> Semua stok barang aman, tidak ada yang perlu diisi ulang.
</div>
<?php endif; ?>
