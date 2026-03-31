<?php
$versi = '1.0.0';
$namaToko = htmlspecialchars($settings['nama_toko'] ?? 'Kasir Warung');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-question-circle"></i> Bantuan & Panduan</h4>
    <span class="badge bg-success fs-6">v<?= $versi ?></span>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <!-- Navigasi Bantuan -->
        <div class="card shadow-sm sticky-top" style="top: 70px;">
            <div class="card-header bg-success text-white">
                <i class="bi bi-list-ul"></i> Daftar Isi
            </div>
            <div class="list-group list-group-flush">
                <a href="#mulai-cepat" class="list-group-item list-group-item-action"><i class="bi bi-lightning"></i> Mulai Cepat</a>
                <a href="#menu-kasir" class="list-group-item list-group-item-action"><i class="bi bi-calculator"></i> Kasir</a>
                <a href="#menu-barang" class="list-group-item list-group-item-action"><i class="bi bi-box-seam"></i> Kelola Barang</a>
                <a href="#menu-riwayat" class="list-group-item list-group-item-action"><i class="bi bi-receipt"></i> Riwayat</a>
                <a href="#menu-laporan" class="list-group-item list-group-item-action"><i class="bi bi-graph-up"></i> Laporan</a>
                <a href="#menu-struk" class="list-group-item list-group-item-action"><i class="bi bi-printer"></i> Cetak Struk</a>
                <a href="#menu-pengaturan" class="list-group-item list-group-item-action"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="#reset-database" class="list-group-item list-group-item-action text-danger"><i class="bi bi-arrow-counterclockwise"></i> Reset Database</a>
                <a href="#keyboard" class="list-group-item list-group-item-action"><i class="bi bi-keyboard"></i> Tips & Trik</a>
            </div>
        </div>
    </div>

    <div class="col-lg-9 mb-4">

        <!-- Mulai Cepat -->
        <div class="card shadow-sm mb-3" id="mulai-cepat">
            <div class="card-header bg-success text-white">
                <i class="bi bi-lightning"></i> Mulai Cepat
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">1</div>
                            <h6>Tambah Barang</h6>
                            <small class="text-muted">Buka menu <strong>Barang</strong>, isi form dan klik Tambah</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">2</div>
                            <h6>Buka Kasir</h6>
                            <small class="text-muted">Cari barang, masukkan ke keranjang</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">3</div>
                            <h6>Bayar & Simpan</h6>
                            <small class="text-muted">Input uang bayar, klik Simpan Transaksi</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">4</div>
                            <h6>Cetak Struk</h6>
                            <small class="text-muted">Struk otomatis terbuka untuk dicetak</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kasir -->
        <div class="card shadow-sm mb-3" id="menu-kasir">
            <div class="card-header bg-success text-white">
                <i class="bi bi-calculator"></i> Halaman Kasir
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Cara Transaksi:</h6>
                <ol>
                    <li>Ketik nama barang atau scan barcode di kolom <strong>Cari Barang</strong></li>
                    <li>Pilih barang dari dropdown — otomatis masuk ke keranjang</li>
                    <li>Atur jumlah (Qty) dengan tombol <strong>+</strong> / <strong>-</strong> atau ketik manual</li>
                    <li>Total belanja otomatis terhitung</li>
                    <li>Masukkan <strong>Uang Bayar</strong> atau klik tombol nominal cepat (Uang Pas, 10rb, 20rb, dll)</li>
                    <li>Kembalian otomatis terhitung</li>
                    <li>Klik <strong>Simpan Transaksi</strong> — struk otomatis terbuka</li>
                </ol>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Stok barang otomatis berkurang setelah transaksi disimpan.
                </div>
            </div>
        </div>

        <!-- Kelola Barang -->
        <div class="card shadow-sm mb-3" id="menu-barang">
            <div class="card-header bg-success text-white">
                <i class="bi bi-box-seam"></i> Kelola Barang
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Fitur:</h6>
                <ul>
                    <li><strong>Tambah</strong> — Isi form di sebelah kiri (nama, harga beli, harga jual, stok, satuan, barcode)</li>
                    <li><strong>Edit</strong> — Klik tombol <i class="bi bi-pencil text-primary"></i> pada baris barang</li>
                    <li><strong>Hapus</strong> — Klik tombol <i class="bi bi-trash text-danger"></i> (ada konfirmasi)</li>
                </ul>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Barcode</strong> bersifat unik — tidak bisa ada 2 barang dengan barcode yang sama.
                </div>
            </div>
        </div>

        <!-- Riwayat -->
        <div class="card shadow-sm mb-3" id="menu-riwayat">
            <div class="card-header bg-success text-white">
                <i class="bi bi-receipt"></i> Riwayat Transaksi
            </div>
            <div class="card-body">
                <ul>
                    <li>Pilih tanggal menggunakan filter di atas tabel</li>
                    <li>Klik baris transaksi untuk melihat <strong>detail item</strong> yang dibeli</li>
                    <li>Klik tombol <i class="bi bi-printer text-success"></i> untuk cetak ulang struk</li>
                    <li>Ringkasan total transaksi dan omzet ditampilkan di kartu atas</li>
                </ul>
            </div>
        </div>

        <!-- Laporan -->
        <div class="card shadow-sm mb-3" id="menu-laporan">
            <div class="card-header bg-success text-white">
                <i class="bi bi-graph-up"></i> Laporan Bulanan
            </div>
            <div class="card-body">
                <ul>
                    <li>Pilih bulan untuk melihat rekap omzet harian</li>
                    <li>Tersedia ringkasan: <strong>Total Transaksi</strong>, <strong>Omzet</strong>, <strong>Laba Kotor</strong>, dan <strong>Rata-rata per Transaksi</strong></li>
                    <li><strong>Laba Kotor</strong> = Harga Jual - Harga Beli (modal)</li>
                    <li>Tabel <strong>Barang Terlaris</strong> menampilkan 10 produk paling laku</li>
                    <li>Klik tanggal di tabel harian untuk langsung ke riwayat transaksi hari itu</li>
                    <li>Klik <strong>Cetak Laporan</strong> untuk print</li>
                </ul>
            </div>
        </div>

        <!-- Struk -->
        <div class="card shadow-sm mb-3" id="menu-struk">
            <div class="card-header bg-success text-white">
                <i class="bi bi-printer"></i> Cetak Struk
            </div>
            <div class="card-body">
                <ul>
                    <li>Struk otomatis muncul setelah transaksi berhasil disimpan</li>
                    <li>Desain struk untuk printer thermal <strong>58mm</strong></li>
                    <li>Struk menampilkan: logo, nama toko, alamat, telepon, daftar item, total, bayar, kembalian</li>
                    <li>Bisa cetak ulang dari halaman <strong>Riwayat</strong></li>
                    <li>Ubah informasi toko di menu <strong>Pengaturan</strong></li>
                </ul>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Untuk printer thermal, pastikan driver printer sudah terinstall dan pilih ukuran kertas 58mm saat print.
                </div>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="card shadow-sm mb-3" id="menu-pengaturan">
            <div class="card-header bg-success text-white">
                <i class="bi bi-gear"></i> Pengaturan
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Yang bisa diatur:</h6>
                <table class="table table-sm">
                    <tr><td><strong>Nama Toko</strong></td><td>Tampil di navbar & struk</td></tr>
                    <tr><td><strong>Alamat & Telepon</strong></td><td>Tampil di struk</td></tr>
                    <tr><td><strong>Footer Struk</strong></td><td>Teks di bagian bawah struk</td></tr>
                    <tr><td><strong>Warna Header</strong></td><td>Warna navbar menu (pilih dari color picker atau preset)</td></tr>
                    <tr><td><strong>Logo Toko</strong></td><td>Upload logo (PNG/JPG, maks 2MB) — tampil di navbar & struk</td></tr>
                </table>
            </div>
        </div>

        <!-- Reset Database -->
        <div class="card shadow-sm mb-3 border-danger" id="reset-database">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle"></i> Reset Database
            </div>
            <div class="card-body">
                <p>Jika ingin <strong>menghapus semua data</strong> dan mulai dari awal:</p>

                <div class="alert alert-danger">
                    <strong>PERINGATAN:</strong> Tindakan ini akan menghapus SEMUA data (barang, transaksi, pengaturan). Tidak dapat dibatalkan!
                </div>

                <h6 class="fw-bold">Cara Reset:</h6>
                <ol>
                    <li>Tutup aplikasi</li>
                    <li>Hapus file database:
                        <ul>
                            <li><strong>Mode Development:</strong> hapus file <code>database/kasir.db</code></li>
                            <li><strong>Mode PHP Desktop:</strong> hapus file <code>www/database/kasir.db</code></li>
                        </ul>
                    </li>
                    <li>Buka kembali aplikasi — database baru akan otomatis dibuat</li>
                </ol>

                <h6 class="fw-bold mt-3">Atau gunakan tombol di bawah (reset transaksi saja):</h6>
                <form method="POST" action="api.php?action=reset_transaksi"
                      onsubmit="return confirm('YAKIN hapus semua data transaksi? Tindakan ini TIDAK BISA dibatalkan!');">
                    <button type="button" class="btn btn-outline-danger" id="btnResetTransaksi">
                        <i class="bi bi-trash"></i> Reset Semua Transaksi
                    </button>
                    <button type="button" class="btn btn-outline-dark ms-2" id="btnResetAll">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Seluruh Database
                    </button>
                </form>
            </div>
        </div>

        <!-- Tips & Trik -->
        <div class="card shadow-sm mb-3" id="keyboard">
            <div class="card-header bg-success text-white">
                <i class="bi bi-keyboard"></i> Tips & Trik
            </div>
            <div class="card-body">
                <ul>
                    <li>Gunakan <strong>barcode scanner</strong> untuk input barang lebih cepat di halaman kasir</li>
                    <li>Stok berwarna <span class="badge bg-danger">merah</span> artinya tinggal ≤5, segera restok</li>
                    <li>Dashboard menampilkan peringatan barang yang stoknya hampir habis</li>
                    <li>Laporan bulanan bisa langsung dicetak (Ctrl+P) untuk arsip</li>
                    <li>Jika menggunakan PHP Desktop, semua data tersimpan lokal di komputer — tidak perlu internet</li>
                </ul>
            </div>
        </div>

        <!-- Info Teknis -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-code-slash"></i> Informasi Teknis
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td><strong>Nama Aplikasi</strong></td><td><strong>Lutfi POS</strong></td></tr>
                    <tr><td><strong>Versi Aplikasi</strong></td><td>v<?= $versi ?></td></tr>
                    <tr><td><strong>Tech Stack</strong></td><td>PHP Native + SQLite + Bootstrap 5 + jQuery</td></tr>
                    <tr><td><strong>Database</strong></td><td>SQLite (file: <code>database/kasir.db</code>)</td></tr>
                    <tr><td><strong>Lebar Struk</strong></td><td>58mm (thermal printer)</td></tr>
                    <tr><td><strong>PHP Desktop</strong></td><td>Chrome v130.1 + PHP 8.3</td></tr>
                </table>
                <div class="alert alert-secondary mt-3 mb-0">
                    <i class="bi bi-shield-lock"></i>
                    <strong>Lutfi POS</strong> adalah nama resmi aplikasi ini. Nama ini tidak boleh diubah atau diganti oleh siapapun yang menggunakan, memodifikasi, atau mendistribusikan ulang source code ini.
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btnResetTransaksi').addEventListener('click', function () {
        if (!confirm('YAKIN hapus semua data transaksi?\nTindakan ini TIDAK BISA dibatalkan!')) return;
        if (!confirm('Ketik YAKIN untuk konfirmasi. Lanjutkan?')) return;

        fetch('api.php?action=reset_transaksi', { method: 'POST' })
            .then(r => r.json())
            .then(res => {
                alert(res.message);
                if (res.success) location.reload();
            });
    });

    document.getElementById('btnResetAll').addEventListener('click', function () {
        if (!confirm('PERINGATAN: Ini akan menghapus SEMUA data (barang, transaksi, pengaturan)!\nLanjutkan?')) return;
        if (!confirm('TERAKHIR KALI: Data akan hilang permanen. Yakin?')) return;

        fetch('api.php?action=reset_all', { method: 'POST' })
            .then(r => r.json())
            .then(res => {
                alert(res.message);
                if (res.success) location.href = 'index.php';
            });
    });
});
</script>
