# Kasir Warung — Lutfi POS

Aplikasi Point of Sale (POS) untuk warung dan toko kecil. Berjalan secara offline di komputer tanpa perlu internet, database tersimpan lokal. Bisa dijalankan di browser (development) atau sebagai aplikasi desktop Windows via PHP Desktop.

## Fitur Utama

- **Kasir / POS** — Cari barang atau scan barcode, keranjang belanja, hitung kembalian otomatis, tombol nominal cepat
- **Manajemen Barang** — CRUD barang, stok minimal per barang, barcode otomatis (auto-generate), satuan kustom (bisa tambah sendiri)
- **Cetak Barcode** — Pilih barang, atur jumlah cetak, preview & print barcode (format CODE128)
- **Riwayat Transaksi** — Filter per tanggal, lihat detail item, cetak ulang struk
- **Laporan Bulanan** — Omzet harian, laba kotor, barang terlaris, bisa dicetak
- **Cetak Struk** — Desain untuk printer thermal 58mm, otomatis muncul setelah transaksi
- **Dashboard** — Ringkasan transaksi & omzet hari ini, laba harian, peringatan stok rendah/habis
- **Pengaturan Toko** — Nama, alamat, telepon, logo, warna header, footer struk
- **Data Demo** — Data contoh langsung tersedia saat pertama buka, bisa di-reset kapan saja

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP Native (tanpa framework) |
| Database | SQLite (file-based, tanpa server) |
| Frontend | Bootstrap 5.3, Bootstrap Icons |
| Library JS | jQuery, Select2, JsBarcode |
| Desktop | PHP Desktop (Chromium + PHP embedded) |

## Struktur Direktori

```
kasir-warung/
├── config/
│   ├── database.php         # Koneksi PDO SQLite + auto-init
│   ├── helpers.php          # Helper: ambil pengaturan
│   ├── init_db.php          # Buat tabel + data demo
│   └── migrate.php          # Migrasi otomatis (kolom baru, tabel baru)
├── controllers/
│   ├── BarangController.php # CRUD barang + generate barcode
│   └── PengaturanController.php # Settings, reset DB, isi demo, satuan
├── views/
│   ├── layouts/             # Header (navbar) & footer
│   ├── kasir/               # Dashboard & halaman POS
│   ├── barang/              # Form & tabel barang
│   ├── barcode/             # Cetak barcode
│   ├── transaksi/           # Riwayat transaksi
│   ├── laporan/             # Laporan bulanan
│   ├── pengaturan/          # Pengaturan toko
│   └── bantuan/             # Panduan penggunaan
├── public/                  # Document root
│   ├── index.php            # Entry point & router
│   ├── api.php              # AJAX endpoints (search, transaksi, barcode, satuan)
│   ├── struk.php            # Cetak struk thermal 58mm
│   ├── css/style.css
│   ├── js/
│   │   ├── app.js           # Script umum
│   │   ├── kasir.js         # Logic POS & keranjang
│   │   └── barcode.js       # Generate & cetak barcode
│   └── uploads/             # Logo toko
├── database/                # File SQLite (auto-generated)
├── phpdesktop/              # PHP Desktop binary (download terpisah)
├── dist/                    # Hasil build
├── build.sh                 # Build script (Linux/Mac)
├── build.bat                # Build script (Windows)
├── install.txt              # Panduan instalasi (bypass SmartScreen)
├── phpdesktop-settings.json # Konfigurasi PHP Desktop
└── .gitignore
```

## Cara Menjalankan

### Development (di browser)

**Prasyarat:** PHP 7.4+ dengan ekstensi SQLite

```bash
# Ubuntu/Debian
sudo apt install php-cli php-sqlite3

# Jalankan
cd public
php -S localhost:8000
```

Buka http://localhost:8000 — database dan data demo otomatis dibuat saat pertama akses.

### Desktop (Windows .exe)

1. **Download PHP Desktop** dari https://github.com/nicengi/phpdesktop/releases (versi Chrome)
2. Ekstrak ke folder `phpdesktop/` di root project
3. Pastikan ekstensi SQLite aktif di `phpdesktop/php/php.ini`:
   ```ini
   extension=php_pdo_sqlite.dll
   extension=php_sqlite3.dll
   ```
4. Jalankan build:
   ```bash
   ./build.sh      # Linux/Mac
   build.bat        # Windows
   ```
5. Hasil build ada di `dist/KasirWarung/` — jalankan `kasir-warung.exe`
6. Untuk distribusi: `cd dist && zip -r KasirWarung.zip KasirWarung/`

> Jika muncul peringatan Windows SmartScreen, lihat file `CARA-INSTALL.txt` di dalam folder hasil build.

## Alur Penggunaan

```
1. Buka aplikasi → data demo sudah terisi
2. Coba fitur kasir, barang, laporan
3. Jika sudah siap pakai data asli → Pengaturan > Reset Database
4. Isi data toko di Pengaturan (nama, alamat, logo)
5. Tambah barang → barcode otomatis di-generate
6. Mulai transaksi di halaman Kasir
7. Lihat laporan di halaman Laporan
```

## Fitur Detail

### Barcode

- Barcode otomatis di-generate jika dikosongkan (format: `KW` + tanggal + 4 digit acak, contoh: `KW2604018472`)
- Barcode bisa diinput manual (dicek duplikat secara real-time)
- Halaman **Cetak Barcode** untuk print label barcode (pilih barang, atur jumlah, preview, cetak)
- Format: CODE128 (kompatibel dengan semua barcode scanner)

### Satuan Kustom

- Satuan bawaan: pcs, kg, liter, pack, lusin, dus, botol, sachet
- Butuh satuan lain? Pilih **"+ Lainnya..."** di dropdown satuan saat tambah/edit barang
- Satuan kustom bisa dihapus (dengan opsi ganti satuan di semua barang yang memakainya)

### Stok Minimal

- Setiap barang punya batas **stok minimal** (default: 5)
- Dashboard menampilkan peringatan barang yang stoknya di bawah minimal atau habis
- Indikator warna di tabel barang (merah = di bawah minimal, hijau = aman)

### Data Demo & Reset

- Saat pertama kali dibuka, aplikasi terisi 15 barang contoh + 5 transaksi demo
- Banner kuning di dashboard menandakan mode demo aktif
- Di **Pengaturan**: tombol **Isi Data Demo** (reset + isi contoh) dan **Reset Database** (kosongkan semua)
- Pengaturan toko tetap tersimpan setelah reset

## API Endpoints

| Endpoint | Method | Fungsi |
|----------|--------|--------|
| `api.php?action=search_barang&q=...` | GET | Cari barang (nama/barcode) untuk POS |
| `api.php?action=simpan_transaksi` | POST | Simpan transaksi + kurangi stok |
| `api.php?action=check_barcode&barcode=...` | GET | Cek duplikat barcode |
| `api.php?action=tambah_satuan&nama=...` | GET | Tambah satuan kustom |
| `api.php?action=hapus_satuan` | POST | Hapus satuan + ganti di barang |
| `api.php?action=cek_satuan_dipakai&nama=...` | GET | Cek jumlah barang yang pakai satuan |
| `api.php?action=reset_transaksi` | POST | Hapus semua transaksi |
| `api.php?action=reset_all` | POST | Reset seluruh database |

## Lisensi

Bebas digunakan untuk keperluan pribadi dan komersial.

**Lutfi POS** adalah nama resmi aplikasi ini dan tidak boleh diubah atau dihapus dari source code.
