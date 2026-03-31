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
                <a href="#menu-barcode" class="list-group-item list-group-item-action"><i class="bi bi-upc-scan"></i> Barcode</a>
                <a href="#menu-riwayat" class="list-group-item list-group-item-action"><i class="bi bi-receipt"></i> Riwayat</a>
                <a href="#menu-laporan" class="list-group-item list-group-item-action"><i class="bi bi-graph-up"></i> Laporan</a>
                <a href="#menu-struk" class="list-group-item list-group-item-action"><i class="bi bi-printer"></i> Cetak Struk</a>
                <a href="#menu-pengaturan" class="list-group-item list-group-item-action"><i class="bi bi-gear"></i> Pengaturan</a>
                <a href="#menu-demo" class="list-group-item list-group-item-action"><i class="bi bi-database"></i> Data Demo & Reset</a>
                <a href="#keyboard" class="list-group-item list-group-item-action"><i class="bi bi-keyboard"></i> Tips & Trik</a>
                <a href="#info-teknis" class="list-group-item list-group-item-action"><i class="bi bi-code-slash"></i> Info Teknis</a>
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
                <p class="text-muted mb-3">Aplikasi sudah terisi data demo saat pertama dibuka. Ikuti langkah berikut untuk mulai menggunakan:</p>
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">1</div>
                            <h6>Atur Toko</h6>
                            <small class="text-muted">Buka <strong>Pengaturan</strong>, isi nama toko, alamat, dan upload logo</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">2</div>
                            <h6>Tambah Barang</h6>
                            <small class="text-muted">Buka menu <strong>Barang</strong>, isi form dan klik Tambah. Barcode otomatis dibuat</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">3</div>
                            <h6>Mulai Kasir</h6>
                            <small class="text-muted">Cari barang atau scan barcode, masukkan ke keranjang, bayar</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">4</div>
                            <h6>Cetak Struk</h6>
                            <small class="text-muted">Struk otomatis terbuka setelah transaksi disimpan</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> Jika ingin mulai dengan data kosong, buka <strong>Pengaturan</strong> dan klik <strong>Reset Database</strong>.
                </div>
            </div>
        </div>

        <!-- Kasir -->
        <div class="card shadow-sm mb-3" id="menu-kasir">
            <div class="card-header bg-success text-white">
                <i class="bi bi-calculator"></i> Halaman Kasir (POS)
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Cara Transaksi:</h6>
                <ol>
                    <li>Ketik nama barang atau <strong>scan barcode</strong> di kolom pencarian</li>
                    <li>Pilih barang dari dropdown — otomatis masuk ke keranjang</li>
                    <li>Atur jumlah (Qty) dengan tombol <strong>+</strong> / <strong>-</strong> atau ketik langsung</li>
                    <li>Total belanja otomatis terhitung di bagian bawah</li>
                    <li>Masukkan <strong>Uang Bayar</strong> atau klik tombol nominal cepat:
                        <div class="mt-1 mb-2">
                            <span class="badge bg-secondary">Uang Pas</span>
                            <span class="badge bg-secondary">Rp 10.000</span>
                            <span class="badge bg-secondary">Rp 20.000</span>
                            <span class="badge bg-secondary">Rp 50.000</span>
                            <span class="badge bg-secondary">Rp 100.000</span>
                        </div>
                    </li>
                    <li>Kembalian otomatis terhitung</li>
                    <li>Klik <strong>Simpan Transaksi</strong> — struk otomatis terbuka untuk dicetak</li>
                </ol>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Stok barang otomatis berkurang setelah transaksi disimpan. Jika stok tidak cukup, transaksi akan ditolak.
                </div>
            </div>
        </div>

        <!-- Kelola Barang -->
        <div class="card shadow-sm mb-3" id="menu-barang">
            <div class="card-header bg-success text-white">
                <i class="bi bi-box-seam"></i> Kelola Barang
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Tambah / Edit Barang:</h6>
                <table class="table table-sm mb-3">
                    <tr><td width="150"><strong>Nama Barang</strong></td><td>Wajib diisi</td></tr>
                    <tr><td><strong>Harga Beli</strong></td><td>Harga modal/kulak, untuk perhitungan laba</td></tr>
                    <tr><td><strong>Harga Jual</strong></td><td>Harga yang dikenakan ke pembeli</td></tr>
                    <tr><td><strong>Stok</strong></td><td>Jumlah barang tersedia saat ini</td></tr>
                    <tr><td><strong>Stok Minimal</strong></td><td>Batas peringatan — jika stok sama atau di bawah angka ini, muncul di dashboard sebagai stok rendah</td></tr>
                    <tr>
                        <td><strong>Satuan</strong></td>
                        <td>Pilih dari daftar (pcs, kg, liter, dll). Butuh satuan lain? Pilih <strong>"+ Lainnya..."</strong> lalu ketik satuan baru</td>
                    </tr>
                    <tr><td><strong>Barcode</strong></td><td>Opsional — jika dikosongkan, barcode otomatis di-generate (format: <code>KW260401xxxx</code>)</td></tr>
                </table>

                <h6 class="fw-bold">Aksi di Tabel Barang:</h6>
                <ul>
                    <li><i class="bi bi-pencil text-primary"></i> <strong>Edit</strong> — Ubah data barang yang sudah ada</li>
                    <li><i class="bi bi-trash text-danger"></i> <strong>Hapus</strong> — Hapus barang (ada konfirmasi)</li>
                </ul>

                <h6 class="fw-bold">Satuan Kustom:</h6>
                <ul>
                    <li>Satuan yang sudah ditambahkan muncul sebagai badge di bawah dropdown</li>
                    <li>Klik tombol <i class="bi bi-x-circle-fill text-danger"></i> pada badge untuk menghapus satuan</li>
                    <li>Jika ada barang yang menggunakan satuan tersebut, akan diminta memilih satuan pengganti</li>
                </ul>

                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Barcode</strong> bersifat unik — tidak bisa ada 2 barang dengan barcode yang sama. Pengecekan duplikat dilakukan secara otomatis saat mengetik.
                </div>
            </div>
        </div>

        <!-- Barcode -->
        <div class="card shadow-sm mb-3" id="menu-barcode">
            <div class="card-header bg-success text-white">
                <i class="bi bi-upc-scan"></i> Cetak Barcode
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Cara Cetak Barcode:</h6>
                <ol>
                    <li>Buka menu <strong>Barcode</strong> di navbar</li>
                    <li>Pilih barang dari dropdown — kode barcode otomatis terisi</li>
                    <li>Atur <strong>label</strong> (nama yang tercetak di atas barcode) dan <strong>harga</strong> jika diperlukan</li>
                    <li>Tentukan <strong>jumlah cetak</strong> (berapa label barcode yang diinginkan)</li>
                    <li>Klik <strong>Generate Preview</strong> untuk melihat hasil</li>
                    <li>Klik <strong>Cetak</strong> untuk membuka dialog print browser</li>
                </ol>

                <h6 class="fw-bold mt-3">Spesifikasi:</h6>
                <table class="table table-sm mb-0">
                    <tr><td><strong>Format</strong></td><td>CODE128 (kompatibel dengan semua barcode scanner)</td></tr>
                    <tr><td><strong>Ukuran Label</strong></td><td>~200px per label, bisa diatur saat print</td></tr>
                    <tr><td><strong>Info pada Label</strong></td><td>Nama barang, barcode, harga (opsional)</td></tr>
                    <tr><td><strong>Library</strong></td><td>JsBarcode — render langsung di browser, tidak perlu internet</td></tr>
                </table>
            </div>
        </div>

        <!-- Riwayat -->
        <div class="card shadow-sm mb-3" id="menu-riwayat">
            <div class="card-header bg-success text-white">
                <i class="bi bi-receipt"></i> Riwayat Transaksi
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Fitur:</h6>
                <ul>
                    <li><strong>Filter tanggal</strong> — Pilih tanggal untuk melihat transaksi pada hari tertentu. Tombol cepat <span class="badge bg-secondary">Hari Ini</span> tersedia</li>
                    <li><strong>Ringkasan</strong> — Kartu di atas menampilkan jumlah transaksi dan total omzet pada tanggal terpilih</li>
                    <li><strong>Detail item</strong> — Klik baris transaksi untuk melihat barang apa saja yang dibeli beserta jumlah dan subtotalnya</li>
                    <li><strong>Cetak ulang struk</strong> — Klik tombol <i class="bi bi-printer text-success"></i> untuk mencetak ulang struk transaksi lama</li>
                </ul>
            </div>
        </div>

        <!-- Laporan -->
        <div class="card shadow-sm mb-3" id="menu-laporan">
            <div class="card-header bg-success text-white">
                <i class="bi bi-graph-up"></i> Laporan Bulanan
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Informasi yang ditampilkan:</h6>
                <table class="table table-sm mb-3">
                    <tr><td width="180"><strong>Total Transaksi</strong></td><td>Jumlah transaksi dalam sebulan</td></tr>
                    <tr><td><strong>Total Omzet</strong></td><td>Total uang masuk dari penjualan</td></tr>
                    <tr><td><strong>Laba Kotor</strong></td><td>Omzet dikurangi harga modal (harga beli). Rumus: Harga Jual - Harga Beli</td></tr>
                    <tr><td><strong>Rata-rata Transaksi</strong></td><td>Omzet dibagi jumlah transaksi</td></tr>
                    <tr><td><strong>Penjualan Harian</strong></td><td>Tabel rekap per hari (tanggal, jumlah transaksi, omzet)</td></tr>
                    <tr><td><strong>Barang Terlaris</strong></td><td>10 barang paling banyak terjual dalam bulan tersebut</td></tr>
                </table>
                <ul>
                    <li>Pilih bulan dengan date picker di atas halaman</li>
                    <li>Klik tanggal di tabel harian untuk langsung ke riwayat transaksi hari itu</li>
                    <li>Klik <strong>Cetak Laporan</strong> untuk print seluruh rekap</li>
                </ul>
            </div>
        </div>

        <!-- Struk -->
        <div class="card shadow-sm mb-3" id="menu-struk">
            <div class="card-header bg-success text-white">
                <i class="bi bi-printer"></i> Cetak Struk
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Isi struk:</h6>
                <ul>
                    <li>Logo toko (jika sudah diupload)</li>
                    <li>Nama toko, alamat, dan telepon</li>
                    <li>Nomor transaksi dan tanggal/waktu</li>
                    <li>Daftar barang: nama, qty, harga, subtotal</li>
                    <li>Total belanja, uang bayar, kembalian</li>
                    <li>Footer struk (bisa diatur di Pengaturan)</li>
                </ul>

                <h6 class="fw-bold mt-3">Pengaturan printer thermal 58mm:</h6>
                <ol>
                    <li>Pastikan driver printer thermal sudah terinstall</li>
                    <li>Saat dialog print muncul, pilih printer thermal sebagai tujuan</li>
                    <li>Atur ukuran kertas ke <strong>58mm</strong> atau <strong>Custom</strong> (lebar 58mm)</li>
                    <li>Atur margin ke <strong>0</strong> atau <strong>minimum</strong></li>
                    <li>Klik Print</li>
                </ol>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Struk juga bisa dicetak ke kertas biasa (A4) — layout akan menyesuaikan.
                </div>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="card shadow-sm mb-3" id="menu-pengaturan">
            <div class="card-header bg-success text-white">
                <i class="bi bi-gear"></i> Pengaturan
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Pengaturan yang tersedia:</h6>
                <table class="table table-sm">
                    <tr><td width="180"><strong>Nama Toko</strong></td><td>Tampil di navbar dan struk. Wajib diisi.</td></tr>
                    <tr><td><strong>Alamat Toko</strong></td><td>Tampil di struk di bawah nama toko</td></tr>
                    <tr><td><strong>Telepon Toko</strong></td><td>Tampil di struk</td></tr>
                    <tr><td><strong>Footer Struk</strong></td><td>Teks tambahan di bagian paling bawah struk (contoh: "Barang yang sudah dibeli tidak dapat dikembalikan")</td></tr>
                    <tr><td><strong>Warna Header</strong></td><td>Warna navbar/menu atas. Pilih dari color picker atau preset warna cepat. Preview langsung terlihat.</td></tr>
                    <tr><td><strong>Logo Toko</strong></td><td>Upload gambar (PNG, JPG, GIF, WebP, maks 2MB). Tampil di navbar dan struk.</td></tr>
                </table>
            </div>
        </div>

        <!-- Data Demo & Reset -->
        <div class="card shadow-sm mb-3 border-warning" id="menu-demo">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-database"></i> Data Demo & Reset Database
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Data Demo:</h6>
                <p>Saat pertama kali dibuka, aplikasi sudah terisi data contoh (15 barang warung + 5 transaksi). Ini berguna untuk mencoba semua fitur sebelum menggunakan data asli.</p>
                <ul>
                    <li>Banner kuning <span class="badge bg-warning text-dark">Mode Demo</span> muncul di dashboard selama data demo aktif</li>
                    <li>Tombol <strong>Isi Data Demo</strong> di Pengaturan untuk mengisi ulang data contoh kapan saja</li>
                </ul>

                <h6 class="fw-bold mt-3">Reset Database:</h6>
                <p>Tersedia di halaman <strong>Pengaturan</strong>:</p>
                <table class="table table-sm mb-3">
                    <tr>
                        <td><span class="badge bg-warning text-dark">Isi Data Demo</span></td>
                        <td>Hapus semua data lalu isi ulang dengan data contoh. Cocok untuk mencoba ulang.</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-danger">Reset Database</span></td>
                        <td>Hapus semua barang dan transaksi. Pengaturan toko tetap tersimpan. Gunakan ini untuk mulai dengan data asli.</td>
                    </tr>
                </table>

                <p>Cara reset lainnya (lebih advanced):</p>
                <div class="d-flex gap-2 mb-3">
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnResetTransaksi">
                        <i class="bi bi-trash"></i> Reset Semua Transaksi
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm" id="btnResetAll">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Seluruh Database
                    </button>
                </div>
                <ul class="text-muted small">
                    <li><strong>Reset Semua Transaksi</strong> — hapus transaksi saja, data barang & pengaturan tetap</li>
                    <li><strong>Reset Seluruh Database</strong> — hapus semua data dan mulai dari awal (termasuk pengaturan)</li>
                </ul>

                <div class="alert alert-danger mb-0">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Peringatan:</strong> Semua tindakan reset bersifat permanen dan tidak bisa dibatalkan. Pastikan data yang penting sudah dicatat atau dicetak.
                </div>
            </div>
        </div>

        <!-- Tips & Trik -->
        <div class="card shadow-sm mb-3" id="keyboard">
            <div class="card-header bg-success text-white">
                <i class="bi bi-keyboard"></i> Tips & Trik
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Barcode Scanner:</h6>
                <ul>
                    <li>Hubungkan barcode scanner USB ke komputer — scanner akan berfungsi seperti keyboard</li>
                    <li>Di halaman <strong>Kasir</strong>, klik kolom pencarian lalu scan barcode — barang otomatis ditemukan</li>
                    <li>Pastikan scanner di-set ke mode <strong>keyboard emulation</strong> (default di kebanyakan scanner)</li>
                </ul>

                <h6 class="fw-bold mt-3">Shortcut Berguna:</h6>
                <ul>
                    <li><kbd>Ctrl</kbd> + <kbd>P</kbd> — Print (untuk cetak struk atau laporan)</li>
                    <li>Di halaman kasir, klik kolom pencarian lalu langsung ketik/scan untuk mencari barang</li>
                    <li>Tombol nominal cepat (Uang Pas, 10rb, dll) mempercepat input pembayaran</li>
                </ul>

                <h6 class="fw-bold mt-3">Tips Lainnya:</h6>
                <ul>
                    <li>Atur <strong>stok minimal</strong> per barang agar dashboard selalu mengingatkan saat stok menipis</li>
                    <li>Gunakan halaman <strong>Cetak Barcode</strong> untuk membuat label harga dengan barcode</li>
                    <li>Laporan bulanan bisa langsung dicetak untuk arsip pembukuan</li>
                    <li>Semua data tersimpan lokal di komputer — tidak perlu internet untuk menggunakan aplikasi ini</li>
                    <li>Backup database secara berkala dengan menyalin file <code>database/kasir.db</code></li>
                </ul>
            </div>
        </div>

        <!-- Info Teknis -->
        <div class="card shadow-sm mb-3" id="info-teknis">
            <div class="card-header bg-secondary text-white">
                <i class="bi bi-code-slash"></i> Informasi Teknis
            </div>
            <div class="card-body">
                <table class="table table-sm mb-3">
                    <tr><td width="180"><strong>Nama Aplikasi</strong></td><td><strong>Lutfi POS</strong></td></tr>
                    <tr><td><strong>Versi</strong></td><td>v<?= $versi ?></td></tr>
                    <tr><td><strong>Backend</strong></td><td>PHP Native (tanpa framework)</td></tr>
                    <tr><td><strong>Database</strong></td><td>SQLite (file: <code>database/kasir.db</code>)</td></tr>
                    <tr><td><strong>Frontend</strong></td><td>Bootstrap 5.3 + Bootstrap Icons</td></tr>
                    <tr><td><strong>Library JS</strong></td><td>jQuery, Select2, JsBarcode</td></tr>
                    <tr><td><strong>Lebar Struk</strong></td><td>58mm (thermal printer)</td></tr>
                    <tr><td><strong>Format Barcode</strong></td><td>CODE128</td></tr>
                    <tr><td><strong>PHP Desktop</strong></td><td>Chromium + PHP embedded</td></tr>
                </table>

                <h6 class="fw-bold">Backup Database:</h6>
                <p>Salin file <code>database/kasir.db</code> ke lokasi aman (flashdisk, cloud, dll). Untuk restore, timpa file tersebut dengan backup lalu buka ulang aplikasi.</p>

                <div class="alert alert-secondary mb-0">
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
        appConfirm('YAKIN hapus semua data transaksi? Tindakan ini TIDAK BISA dibatalkan!', function () {
            fetch('api.php?action=reset_transaksi', { method: 'POST' })
                .then(r => r.json())
                .then(res => {
                    appAlert(res.message, res.success ? 'success' : 'danger');
                    if (res.success) setTimeout(function () { location.reload(); }, 1500);
                });
        });
    });

    document.getElementById('btnResetAll').addEventListener('click', function () {
        appConfirm('PERINGATAN: Ini akan menghapus SEMUA data (barang, transaksi, pengaturan)! Data akan hilang permanen.', function () {
            fetch('api.php?action=reset_all', { method: 'POST' })
                .then(r => r.json())
                .then(res => {
                    appAlert(res.message, res.success ? 'success' : 'danger');
                    if (res.success) setTimeout(function () { location.href = 'index.php'; }, 1500);
                });
        });
    });
});
</script>
