<?php

function initDatabase(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS barang (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama TEXT NOT NULL,
            harga_modal REAL NOT NULL DEFAULT 0,
            harga_jual REAL NOT NULL DEFAULT 0,
            stok INTEGER NOT NULL DEFAULT 0,
            satuan TEXT NOT NULL DEFAULT 'pcs',
            barcode TEXT UNIQUE,
            created_at TEXT DEFAULT (datetime('now','localtime')),
            updated_at TEXT DEFAULT (datetime('now','localtime'))
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS transaksi (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tanggal TEXT DEFAULT (datetime('now','localtime')),
            total_harga REAL NOT NULL DEFAULT 0,
            bayar REAL NOT NULL DEFAULT 0,
            kembalian REAL NOT NULL DEFAULT 0
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS detail_transaksi (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            transaksi_id INTEGER NOT NULL,
            barang_id INTEGER NOT NULL,
            nama_barang TEXT NOT NULL,
            harga_jual REAL NOT NULL,
            jumlah INTEGER NOT NULL DEFAULT 1,
            subtotal REAL NOT NULL DEFAULT 0,
            FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
            FOREIGN KEY (barang_id) REFERENCES barang(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pengaturan (
            kunci TEXT PRIMARY KEY,
            nilai TEXT NOT NULL DEFAULT ''
        )
    ");

    // Default settings
    $defaults = [
        'nama_toko'    => 'Kasir Warung',
        'alamat_toko'  => 'Jl. Contoh Alamat No. 123',
        'telepon_toko' => '08xx-xxxx-xxxx',
        'footer_struk' => 'Barang yang sudah dibeli tidak dapat dikembalikan',
        'warna_header' => '#198754',
        'logo_toko'    => '',
    ];
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO pengaturan (kunci, nilai) VALUES (:k, :v)");
    foreach ($defaults as $k => $v) {
        $stmt->execute([':k' => $k, ':v' => $v]);
    }

    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_barang_barcode ON barang(barcode)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_detail_transaksi_id ON detail_transaksi(transaksi_id)");
}
