<?php
require_once __DIR__ . '/../../controllers/BarangController.php';

$flash = handleBarangAction($pdo);
$barangList = getAllBarang($pdo);

$edit = null;
if (isset($_GET['edit'])) {
    $edit = getBarangById($pdo, (int) $_GET['edit']);
}
?>

<?php if ($flash): ?>
    <?php [$type, $msg] = explode('|', $flash, 2); ?>
    <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Form Tambah / Edit -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="bi bi-<?= $edit ? 'pencil-square' : 'plus-circle' ?>"></i>
                <?= $edit ? 'Edit Barang' : 'Tambah Barang' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=barang">
                    <input type="hidden" name="action" value="<?= $edit ? 'update' : 'create' ?>">
                    <?php if ($edit): ?>
                        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required
                               value="<?= htmlspecialchars($edit['nama'] ?? '') ?>"
                               placeholder="Contoh: Indomie Goreng">
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Harga Beli</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_modal" class="form-control" min="0" step="100"
                                       value="<?= $edit['harga_modal'] ?? 0 ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_jual" class="form-control" min="0" step="100"
                                       value="<?= $edit['harga_jual'] ?? 0 ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0"
                                   value="<?= $edit['stok'] ?? 0 ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" class="form-select">
                                <?php
                                $satuanList = ['pcs', 'kg', 'liter', 'pack', 'lusin', 'dus', 'botol', 'sachet'];
                                $currentSatuan = $edit['satuan'] ?? 'pcs';
                                foreach ($satuanList as $s): ?>
                                    <option value="<?= $s ?>" <?= $currentSatuan === $s ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control"
                               value="<?= htmlspecialchars($edit['barcode'] ?? '') ?>"
                               placeholder="Opsional">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-<?= $edit ? 'check-lg' : 'plus-lg' ?>"></i>
                            <?= $edit ? 'Simpan Perubahan' : 'Tambah Barang' ?>
                        </button>
                        <?php if ($edit): ?>
                            <a href="index.php?page=barang" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data Barang -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-box-seam"></i> Data Barang</span>
                <span class="badge bg-light text-success"><?= count($barangList) ?> item</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($barangList)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Belum ada data barang. Silakan tambahkan barang baru.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">#</th>
                                    <th>Nama</th>
                                    <th class="text-end">H. Beli</th>
                                    <th class="text-end">H. Jual</th>
                                    <th class="text-center">Stok</th>
                                    <th>Satuan</th>
                                    <th class="text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($barangList as $i => $b): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($b['nama']) ?></strong>
                                        <?php if ($b['barcode']): ?>
                                            <br><small class="text-muted"><i class="bi bi-upc-scan"></i> <?= htmlspecialchars($b['barcode']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">Rp <?= number_format($b['harga_modal'], 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($b['harga_jual'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php if ($b['stok'] <= 5): ?>
                                            <span class="badge bg-danger"><?= $b['stok'] ?></span>
                                        <?php elseif ($b['stok'] <= 20): ?>
                                            <span class="badge bg-warning text-dark"><?= $b['stok'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?= $b['stok'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= ucfirst($b['satuan']) ?></td>
                                    <td class="text-center">
                                        <a href="index.php?page=barang&edit=<?= $b['id'] ?>"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="index.php?page=barang"
                                              class="d-inline" onsubmit="return confirm('Hapus barang &quot;<?= htmlspecialchars($b['nama'], ENT_QUOTES) ?>&quot;?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
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
