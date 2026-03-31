#!/bin/bash

# ============================================================
# Build Script — Kasir Warung → PHP Desktop (.exe)
# ============================================================

set -e

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
PHPDESKTOP_DIR="$PROJECT_DIR/phpdesktop"
DIST_DIR="$PROJECT_DIR/dist"
APP="KasirWarung"

echo ""
echo "  ╔══════════════════════════════════════╗"
echo "  ║   Build Kasir Warung → PHP Desktop   ║"
echo "  ╚══════════════════════════════════════╝"
echo ""

# --- Cek PHP Desktop ---
if [ ! -d "$PHPDESKTOP_DIR" ]; then
    echo "  [✗] Folder phpdesktop/ belum ada."
    echo ""
    echo "  Download: https://github.com/nicengi/phpdesktop/releases"
    echo "  Ekstrak ke: $PHPDESKTOP_DIR/"
    exit 1
fi

EXE=$(find "$PHPDESKTOP_DIR" -maxdepth 1 -name "phpdesktop-chrome*.exe" 2>/dev/null | head -1)
if [ -z "$EXE" ]; then
    echo "  [✗] phpdesktop-chrome.exe tidak ditemukan"
    exit 1
fi
echo "  [✓] PHP Desktop ditemukan"

# --- Cek SQLite extension di php.ini ---
PHPINI=$(find "$PHPDESKTOP_DIR/php" -name "php.ini" 2>/dev/null | head -1)
if [ -n "$PHPINI" ]; then
    if grep -q "^;.*extension=pdo_sqlite" "$PHPINI" 2>/dev/null; then
        echo "  [!] PERINGATAN: pdo_sqlite belum aktif di php.ini"
        echo "      Buka: $PHPINI"
        echo "      Hapus titik koma (;) di depan: extension=pdo_sqlite"
        echo ""
    fi
    if grep -q "^;.*extension=sqlite3" "$PHPINI" 2>/dev/null; then
        echo "  [!] PERINGATAN: sqlite3 belum aktif di php.ini"
        echo "      Hapus titik koma (;) di depan: extension=sqlite3"
        echo ""
    fi
fi

# --- Bersihkan & salin base ---
rm -rf "$DIST_DIR/$APP"
mkdir -p "$DIST_DIR/$APP"
cp -r "$PHPDESKTOP_DIR/"* "$DIST_DIR/$APP/"
echo "  [✓] PHP Desktop base disalin"

# --- settings.json ---
cp "$PROJECT_DIR/phpdesktop-settings.json" "$DIST_DIR/$APP/settings.json"
echo "  [✓] settings.json dikonfigurasi"

# --- Bangun www/ ---
rm -rf "$DIST_DIR/$APP/www"
mkdir -p "$DIST_DIR/$APP/www"

# Salin semua ke www/ (flat: config, controllers, views sejajar index.php)
cp -r "$PROJECT_DIR/config"      "$DIST_DIR/$APP/www/config"
cp -r "$PROJECT_DIR/controllers" "$DIST_DIR/$APP/www/controllers"
cp -r "$PROJECT_DIR/models"      "$DIST_DIR/$APP/www/models"
cp -r "$PROJECT_DIR/views"       "$DIST_DIR/$APP/www/views"
cp -r "$PROJECT_DIR/public/"*    "$DIST_DIR/$APP/www/"
mkdir -p "$DIST_DIR/$APP/www/database"
mkdir -p "$DIST_DIR/$APP/www/uploads"
echo "  [✓] Aplikasi disalin ke www/"

# --- Fix path relatif ---
# Di development: public/index.php → __DIR__/../config  (naik 1 level)
# Di PHP Desktop: www/index.php & www/config/ sejajar    (tidak perlu naik)
echo "  [~] Menyesuaikan path..."

# Fix file di www/ root (dulu di public/)
# __DIR__ . '/../config' → __DIR__ . '/config' (karena sekarang sejajar)
for f in "$DIST_DIR/$APP/www/index.php" "$DIST_DIR/$APP/www/api.php" "$DIST_DIR/$APP/www/struk.php"; do
    if [ -f "$f" ]; then
        sed -i "s|__DIR__ \. '/\.\./|__DIR__ . '/|g;s|__DIR__ \. \"/\.\./|__DIR__ . \"/|g" "$f"
    fi
done

# config/database.php: __DIR__/../database → tetap benar (www/config/../database = www/database) ✓
# views/xxx/yyy.php: __DIR__/../../controllers → tetap benar (www/views/xxx/../../controllers = www/controllers) ✓
# Path views/xxx/yyy.php → ../../controllers/ tetap benar karena struktur relatif sama ✓

echo "  [✓] Path disesuaikan"

# --- Salin panduan instalasi ke dist/ ---
cp "$PROJECT_DIR/install.txt" "$DIST_DIR/$APP/CARA-INSTALL.txt"
echo "  [✓] CARA-INSTALL.txt disalin ke $APP/"

# --- Rename exe → kasir-warung.exe ---
EXE_NAME=$(basename "$EXE")
if [ -f "$DIST_DIR/$APP/$EXE_NAME" ]; then
    mv "$DIST_DIR/$APP/$EXE_NAME" "$DIST_DIR/$APP/kasir-warung.exe"
    echo "  [✓] $EXE_NAME → kasir-warung.exe"
fi

echo ""
echo "  ══════════════════════════════════════"
echo "  BUILD SELESAI!"
echo "  ══════════════════════════════════════"
echo ""
echo "  Output  : $DIST_DIR/$APP/"
echo "  Jalankan: double-click kasir-warung.exe"
echo ""
echo "  Distribusi: cd dist && zip -r $APP.zip $APP/"
echo ""
