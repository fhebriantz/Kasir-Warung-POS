# Kasir Warung (POS)

Aplikasi Point of Sale (POS) sederhana untuk warung/toko kecil. Berbasis web lokal, bisa dijalankan sebagai aplikasi desktop via PHP Desktop.

## Tech Stack

- PHP Native (tanpa framework)
- SQLite (database file-based)
- Bootstrap 5 + Bootstrap Icons
- jQuery + Select2

## Struktur Direktori

```
kasir-warung/
├── config/                  # Konfigurasi
│   ├── database.php         # Koneksi PDO SQLite
│   ├── helpers.php          # Helper functions (pengaturan)
│   └── init_db.php          # Auto-create tabel
├── controllers/             # Logic CRUD
│   ├── BarangController.php
│   └── PengaturanController.php
├── models/                  # (reserved)
├── views/                   # Tampilan (PHP + HTML)
│   ├── layouts/             # Header & footer
│   ├── kasir/               # Dashboard & POS
│   ├── barang/              # CRUD barang
│   ├── transaksi/           # Riwayat transaksi
│   ├── laporan/             # Laporan bulanan
│   ├── pengaturan/          # Settings toko
│   └── bantuan/             # Halaman bantuan
├── public/                  # Document root (web server)
│   ├── index.php            # Entry point / router
│   ├── api.php              # AJAX endpoint
│   ├── struk.php            # Cetak struk thermal 58mm
│   ├── css/style.css
│   ├── js/app.js
│   ├── js/kasir.js
│   └── uploads/             # Logo toko
├── database/                # File SQLite (auto-generated)
│   └── kasir.db
├── phpdesktop/              # PHP Desktop binary (dari release)
├── dist/                    # Hasil build
├── build.sh                 # Build script (Linux/Mac)
├── build.bat                # Build script (Windows)
├── phpdesktop-settings.json # Konfigurasi PHP Desktop
└── .gitignore
```

## Cara Menjalankan (Development)

### Prasyarat

```bash
sudo apt install php-cli php-sqlite3   # Ubuntu/Debian
```

### Jalankan

```bash
cd public
php -S localhost:8000
```

Buka http://localhost:8000 di browser. Database `kasir.db` otomatis ter-generate saat pertama kali diakses.

## Build ke PHP Desktop (Distribusi .exe)

### 1. Download PHP Desktop

Download dari https://github.com/cztomczak/phpdesktop/releases — pilih versi **Chrome** (phpdesktop-chrome-xxx.zip).

### 2. Ekstrak ke project

```bash
mkdir phpdesktop
# Ekstrak isi ZIP ke folder phpdesktop/
# Pastikan phpdesktop/phpdesktop-chrome.exe ada
```

### 3. Cek SQLite extension

Buka `phpdesktop/php/php.ini`, pastikan baris ini aktif (tanpa `;` di depan):

```ini
extension=php_pdo_sqlite.dll
extension=php_sqlite3.dll
```

### 4. Build

```bash
# Linux/Mac
./build.sh

# Windows
build.bat
```

Hasil build: `dist/KasirWarung/`

### 5. Distribusi

```bash
cd dist
zip -r KasirWarung.zip KasirWarung/
```

Kirim `KasirWarung.zip` ke client. Client tinggal extract dan double-click `phpdesktop-chrome.exe`.

## Sistem Upload

- Logo toko diupload via menu **Pengaturan**
- File tersimpan di `public/uploads/`
- Format: PNG, JPG, GIF, WebP (maks 2MB)
- Logo tampil di navbar dan struk

## Reset Database

### Reset total (semua data hilang)

Hapus file `database/kasir.db`, lalu buka aplikasi kembali. Database baru otomatis dibuat.

### Reset via aplikasi

Buka menu **Bantuan** > scroll ke bagian **Reset Database**:

- **Reset Semua Transaksi** — hapus transaksi saja, barang & pengaturan tetap
- **Reset Seluruh Database** — hapus semua data dan mulai dari awal

## Fitur

| Fitur | Deskripsi |
|-------|-----------|
| Dashboard | Statistik & peringatan stok rendah |
| Kasir (POS) | Cari barang (Select2 + AJAX), keranjang, hitung otomatis |
| CRUD Barang | Tambah, edit, hapus barang + barcode |
| Riwayat | Filter per tanggal, detail item, cetak ulang struk |
| Laporan Bulanan | Omzet harian, laba kotor, barang terlaris |
| Cetak Struk | Thermal 58mm, auto-print, data toko dinamis |
| Pengaturan | Nama toko, alamat, logo, warna header, footer struk |
| Bantuan | Panduan lengkap di dalam aplikasi |

## Git Workflow

```bash
# Clone / init
git clone <url> kasir-warung
cd kasir-warung

# Development biasa
git add -A
git commit -m "deskripsi perubahan"

# Buat branch fitur baru
git checkout -b fitur/nama-fitur
# ... coding ...
git add -A
git commit -m "tambah fitur xxx"
git checkout main
git merge fitur/nama-fitur

# Tag versi release
git tag -a v1.0.0 -m "Release versi 1.0.0"

# Push ke remote
git push origin main --tags
```

### Konvensi Commit

```
tambah: fitur baru
ubah: perubahan fitur existing
perbaiki: bug fix
hapus: hapus fitur/file
docs: dokumentasi
```

## Lisensi

Bebas digunakan untuk keperluan pribadi dan komersial.
