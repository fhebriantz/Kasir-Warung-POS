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
                        <div class="col-4">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0"
                                   value="<?= $edit['stok'] ?? 0 ?>">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Stok Min.</label>
                            <input type="number" name="stok_minimal" class="form-control" min="0"
                                   value="<?= $edit['stok_minimal'] ?? 5 ?>"
                                   title="Peringatan muncul jika stok di bawah angka ini">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Satuan</label>
                            <select name="satuan" id="selectSatuan" class="form-select">
                                <?php
                                $satuanBawaan = ['pcs', 'kg', 'liter', 'pack', 'lusin', 'dus', 'botol', 'sachet'];
                                $stmtSatuan = $pdo->query("SELECT nama FROM satuan_kustom ORDER BY nama ASC");
                                $satuanKustom = $stmtSatuan ? $stmtSatuan->fetchAll(PDO::FETCH_COLUMN) : [];
                                $satuanList = array_unique(array_merge($satuanBawaan, $satuanKustom));
                                $currentSatuan = $edit['satuan'] ?? 'pcs';
                                if (!in_array($currentSatuan, $satuanList)) {
                                    $satuanList[] = $currentSatuan;
                                }
                                foreach ($satuanList as $s): ?>
                                    <option value="<?= htmlspecialchars($s) ?>"
                                            <?= $currentSatuan === $s ? 'selected' : '' ?>
                                            <?= in_array($s, $satuanKustom) ? 'data-kustom="1"' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($s)) ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="__lainnya__">+ Lainnya...</option>
                            </select>
                            <input type="text" id="inputSatuanBaru" class="form-control mt-2" style="display:none;"
                                   placeholder="Ketik satuan baru, misal: rim, kodi...">
                            <?php if (!empty($satuanKustom)): ?>
                            <div class="mt-2" id="satuanKustomList">
                                <small class="text-muted">Satuan kustom:</small>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <?php foreach ($satuanKustom as $sk): ?>
                                    <span class="badge bg-light text-dark border d-flex align-items-center gap-1 satuan-badge" data-nama="<?= htmlspecialchars($sk) ?>">
                                        <?= ucfirst(htmlspecialchars($sk)) ?>
                                        <i class="bi bi-x-circle-fill text-danger" role="button" style="cursor:pointer;" title="Hapus satuan"></i>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" id="inputBarcode" class="form-control"
                               value="<?= htmlspecialchars($edit['barcode'] ?? '') ?>"
                               placeholder="Kosongkan untuk generate otomatis">
                        <div id="barcodeWarning" class="invalid-feedback" style="display:none;"></div>
                        <div class="form-text">Jika dikosongkan, barcode akan di-generate otomatis (format: KW + tanggal + 4 digit acak)</div>
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
                                        <?php $minStok = $b['stok_minimal'] ?? 5; ?>
                                        <?php if ($b['stok'] <= $minStok): ?>
                                            <span class="badge bg-danger"><?= $b['stok'] ?></span>
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
                                              class="d-inline" data-confirm="Hapus barang &quot;<?= htmlspecialchars($b['nama'], ENT_QUOTES) ?>&quot;?">
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

<!-- Modal Hapus Satuan -->
<div class="modal fade" id="modalHapusSatuan" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title"><i class="bi bi-trash"></i> Hapus Satuan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Hapus satuan "<strong id="hapusSatuanNama"></strong>"?</p>
                <div id="hapusSatuanInfo" class="alert alert-warning" style="display:none;">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span id="hapusSatuanCount"></span> barang menggunakan satuan ini.
                    Ganti dengan:
                    <select id="hapusSatuanGanti" class="form-select form-select-sm mt-2">
                        <?php
                        $satuanBawaan = ['pcs', 'kg', 'liter', 'pack', 'lusin', 'dus', 'botol', 'sachet'];
                        foreach ($satuanBawaan as $s): ?>
                            <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="btnKonfirmHapusSatuan">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Barcode validation ---
    var inputBarcode = document.getElementById('inputBarcode');
    var barcodeWarning = document.getElementById('barcodeWarning');
    if (inputBarcode) {
        var timer = null;
        var editId = <?= json_encode($edit['id'] ?? 0) ?>;

        inputBarcode.addEventListener('input', function () {
            clearTimeout(timer);
            var val = this.value.trim();
            barcodeWarning.style.display = 'none';
            inputBarcode.classList.remove('is-invalid');
            if (val === '') return;

            timer = setTimeout(function () {
                var url = 'api.php?action=check_barcode&barcode=' + encodeURIComponent(val);
                if (editId > 0) url += '&exclude_id=' + editId;
                fetch(url).then(function(r){ return r.json(); }).then(function(res) {
                    if (res.exists) {
                        barcodeWarning.textContent = res.message;
                        barcodeWarning.style.display = 'block';
                        inputBarcode.classList.add('is-invalid');
                    }
                });
            }, 400);
        });
    }

    // --- Satuan "Lainnya..." ---
    var selectSatuan = document.getElementById('selectSatuan');
    var inputSatuanBaru = document.getElementById('inputSatuanBaru');
    if (selectSatuan && inputSatuanBaru) {
        selectSatuan.addEventListener('change', function () {
            if (this.value === '__lainnya__') {
                inputSatuanBaru.style.display = 'block';
                inputSatuanBaru.focus();
                inputSatuanBaru.required = true;
            } else {
                inputSatuanBaru.style.display = 'none';
                inputSatuanBaru.value = '';
                inputSatuanBaru.required = false;
            }
        });

        inputSatuanBaru.closest('form').addEventListener('submit', function () {
            if (selectSatuan.value === '__lainnya__') {
                var val = inputSatuanBaru.value.trim().toLowerCase();
                if (val === '') return;
                var opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val.charAt(0).toUpperCase() + val.slice(1);
                opt.selected = true;
                opt.dataset.kustom = '1';
                selectSatuan.insertBefore(opt, selectSatuan.querySelector('option[value="__lainnya__"]'));
                fetch('api.php?action=tambah_satuan&nama=' + encodeURIComponent(val));
            }
        });
    }

    // --- Hapus satuan kustom ---
    var hapusSatuanTarget = '';
    var modal = document.getElementById('modalHapusSatuan');
    if (!modal) return;
    var bsModal = new bootstrap.Modal(modal);

    document.querySelectorAll('.satuan-badge i').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var badge = this.closest('.satuan-badge');
            hapusSatuanTarget = badge.dataset.nama;
            document.getElementById('hapusSatuanNama').textContent = hapusSatuanTarget;

            // Cek berapa barang yang pakai satuan ini
            fetch('api.php?action=cek_satuan_dipakai&nama=' + encodeURIComponent(hapusSatuanTarget))
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    var info = document.getElementById('hapusSatuanInfo');
                    if (res.count > 0) {
                        document.getElementById('hapusSatuanCount').textContent = res.count;
                        info.style.display = 'block';
                    } else {
                        info.style.display = 'none';
                    }
                    bsModal.show();
                });
        });
    });

    document.getElementById('btnKonfirmHapusSatuan').addEventListener('click', function () {
        var gantiDengan = document.getElementById('hapusSatuanGanti').value;
        fetch('api.php?action=hapus_satuan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nama: hapusSatuanTarget, ganti_dengan: gantiDengan })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                bsModal.hide();
                location.reload();
            }
        });
    });
});
</script>
