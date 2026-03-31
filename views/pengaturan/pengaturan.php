<?php
require_once __DIR__ . '/../../controllers/PengaturanController.php';

$flash = handlePengaturanAction($pdo);
$settings = getSemuaPengaturan($pdo);
?>

<?php if ($flash): ?>
    <?php [$type, $msg] = explode('|', $flash, 2); ?>
    <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<h4 class="mb-3"><i class="bi bi-gear"></i> Pengaturan Toko</h4>

<div class="row">
    <!-- Kolom Kiri: Info Toko & Warna -->
    <div class="col-lg-7 mb-4">
        <form method="POST" action="index.php?page=pengaturan">
            <input type="hidden" name="action" value="simpan_umum">

            <!-- Info Toko -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-shop"></i> Informasi Toko
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
                        <input type="text" name="nama_toko" class="form-control" required
                               value="<?= htmlspecialchars($settings['nama_toko']) ?>"
                               placeholder="Nama toko Anda">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat_toko" class="form-control"
                               value="<?= htmlspecialchars($settings['alamat_toko']) ?>"
                               placeholder="Alamat toko">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon_toko" class="form-control"
                               value="<?= htmlspecialchars($settings['telepon_toko']) ?>"
                               placeholder="No. telepon">
                    </div>
                </div>
            </div>

            <!-- Layout Struk -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-printer"></i> Layout Struk
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Footer Struk</label>
                        <textarea name="footer_struk" class="form-control" rows="2"
                                  placeholder="Pesan di bagian bawah struk"><?= htmlspecialchars($settings['footer_struk']) ?></textarea>
                        <div class="form-text">Teks yang muncul di bawah struk setelah "Terima Kasih"</div>
                    </div>
                </div>
            </div>

            <!-- Warna Header -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-palette"></i> Tampilan
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Warna Header / Navbar</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="warna_header" class="form-control form-control-color"
                                   id="inputWarna"
                                   value="<?= htmlspecialchars($settings['warna_header']) ?>">
                            <input type="text" class="form-control" style="max-width: 120px;"
                                   id="inputWarnaText"
                                   value="<?= htmlspecialchars($settings['warna_header']) ?>" readonly>
                        </div>
                        <div class="form-text">Klik kotak warna untuk memilih warna header menu</div>
                    </div>

                    <!-- Preset Warna -->
                    <div class="mb-2">
                        <label class="form-label">Pilihan Cepat:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $presets = [
                                '#198754' => 'Hijau',
                                '#0d6efd' => 'Biru',
                                '#dc3545' => 'Merah',
                                '#6f42c1' => 'Ungu',
                                '#fd7e14' => 'Oranye',
                                '#20c997' => 'Teal',
                                '#d63384' => 'Pink',
                                '#212529' => 'Hitam',
                            ];
                            foreach ($presets as $hex => $nama): ?>
                                <button type="button" class="btn btn-sm border btn-preset-warna"
                                        data-warna="<?= $hex ?>"
                                        style="background-color: <?= $hex ?>; width: 36px; height: 36px;"
                                        title="<?= $nama ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mt-3">
                        <label class="form-label">Preview:</label>
                        <div id="previewHeader" class="rounded p-3 text-white d-flex align-items-center gap-2"
                             style="background-color: <?= htmlspecialchars($settings['warna_header']) ?>;">
                            <i class="bi bi-shop fs-5"></i>
                            <span class="fw-bold"><?= htmlspecialchars($settings['nama_toko']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="bi bi-check-lg"></i> Simpan Pengaturan
            </button>
        </form>
    </div>

    <!-- Kolom Kanan: Logo -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="bi bi-image"></i> Logo Toko
            </div>
            <div class="card-body text-center">
                <!-- Preview Logo -->
                <div class="mb-3 p-3 bg-light rounded">
                    <?php if (!empty($settings['logo_toko'])): ?>
                        <img src="uploads/<?= htmlspecialchars($settings['logo_toko']) ?>"
                             alt="Logo Toko" class="img-fluid" style="max-height: 150px;"
                             id="previewLogo">
                    <?php else: ?>
                        <div id="previewLogo" class="text-muted py-4">
                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                            <p class="mb-0 mt-2">Belum ada logo</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upload -->
                <form method="POST" action="index.php?page=pengaturan" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload_logo">
                    <div class="mb-3">
                        <input type="file" name="logo" class="form-control" accept="image/*" id="inputLogo">
                        <div class="form-text">PNG, JPG, GIF, atau WebP. Maks 2MB.</div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload"></i> Upload Logo
                    </button>
                </form>

                <?php if (!empty($settings['logo_toko'])): ?>
                    <hr>
                    <form method="POST" action="index.php?page=pengaturan"
                          onsubmit="return confirm('Hapus logo toko?')">
                        <input type="hidden" name="action" value="hapus_logo">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Hapus Logo
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Preview Struk -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-receipt"></i> Preview Struk
            </div>
            <div class="card-body p-2" style="background: #f0f0f0;">
                <div style="background: #fff; font-family: 'Courier New', monospace; font-size: 11px;
                            max-width: 220px; margin: 0 auto; padding: 10px; line-height: 1.4;">
                    <div class="text-center">
                        <?php if (!empty($settings['logo_toko'])): ?>
                            <img src="uploads/<?= htmlspecialchars($settings['logo_toko']) ?>"
                                 style="max-height: 40px; max-width: 100px;" class="mb-1"><br>
                        <?php endif; ?>
                        <strong style="font-size: 13px;"><?= htmlspecialchars($settings['nama_toko']) ?></strong><br>
                        <span style="font-size: 9px;">
                            <?= htmlspecialchars($settings['alamat_toko']) ?><br>
                            Telp: <?= htmlspecialchars($settings['telepon_toko']) ?>
                        </span>
                    </div>
                    <hr style="border-top: 1px dashed #000; margin: 4px 0;">
                    <div style="font-size: 10px;">
                        No: #0001 &nbsp; 31/03/2026 14:30
                    </div>
                    <hr style="border-top: 1px dashed #000; margin: 4px 0;">
                    <div style="font-size: 10px;">
                        Contoh Barang<br>
                        &nbsp; 2 x 5.000 <span style="float:right;">10.000</span>
                    </div>
                    <hr style="border-top: 1px dashed #000; margin: 4px 0;">
                    <div>
                        <strong>TOTAL <span style="float:right;">Rp 10.000</span></strong>
                    </div>
                    <hr style="border-top: 1px dashed #000; margin: 4px 0;">
                    <div class="text-center" style="font-size: 10px;">
                        <strong>Terima Kasih!</strong><br>
                        <?= nl2br(htmlspecialchars($settings['footer_struk'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputWarna = document.getElementById('inputWarna');
    const inputWarnaText = document.getElementById('inputWarnaText');
    const previewHeader = document.getElementById('previewHeader');

    inputWarna.addEventListener('input', function () {
        inputWarnaText.value = this.value;
        previewHeader.style.backgroundColor = this.value;
    });

    document.querySelectorAll('.btn-preset-warna').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const warna = this.dataset.warna;
            inputWarna.value = warna;
            inputWarnaText.value = warna;
            previewHeader.style.backgroundColor = warna;
        });
    });

    const inputLogo = document.getElementById('inputLogo');
    if (inputLogo) {
        inputLogo.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const container = document.getElementById('previewLogo');
                    if (container.tagName === 'IMG') {
                        container.src = e.target.result;
                    } else {
                        container.innerHTML = '<img src="' + e.target.result + '" class="img-fluid" style="max-height: 150px;">';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
