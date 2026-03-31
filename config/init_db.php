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
            stok_minimal INTEGER NOT NULL DEFAULT 5,
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

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS satuan_kustom (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama TEXT NOT NULL UNIQUE
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

    // Isi data demo saat pertama kali
    isiDataDemo($pdo);
}

function isiDataDemo(PDO $pdo): void
{
    // Kolom: nama, harga_modal, harga_jual, stok, stok_minimal, satuan, barcode
    $dummyBarang = [
        ['Beras 5kg',          45000, 52000, 20, 10, 'pcs', '8991001234'],
        ['Minyak Goreng 1L',   12000, 15000, 30, 10, 'pcs', '8991005678'],
        ['Gula Pasir 1kg',      9500, 12000, 25, 10, 'pcs', '8991009012'],
        ['Kopi Sachet',         1000,  1500, 100, 20, 'pcs', '8991003456'],
        ['Mie Instan',          2500,  3000, 80, 20, 'pcs', '8991007890'],
        ['Telur 1kg',          24000, 28000, 15, 10, 'kg',  '8991002345'],
        ['Sabun Mandi',         3000,  4000, 40, 10, 'pcs', '8991006789'],
        ['Shampo Sachet',       1000,  1500, 60, 15, 'pcs', '8991000123'],
        ['Teh Celup',           5000,  7000, 35, 10, 'pcs', '8991004567'],
        ['Air Mineral 600ml',   2000,  3000, 50, 20, 'pcs', '8991008901'],
        ['Rokok Filter',       18000, 21000, 25, 10, 'pcs', '8991001357'],
        ['Gas Elpiji 3kg',     18000, 20000, 10, 5,  'pcs', '8991002468'],
        ['Deterjen 1kg',       12000, 15000, 20, 10, 'pcs', '8991003579'],
        ['Susu Kotak 250ml',    4000,  5000, 30, 10, 'pcs', '8991004680'],
        ['Roti Tawar',          8000, 10000, 12, 5,  'pcs', '8991005791'],
    ];

    $stmtBarang = $pdo->prepare("
        INSERT INTO barang (nama, harga_modal, harga_jual, stok, stok_minimal, satuan, barcode)
        VALUES (:nama, :modal, :jual, :stok, :stok_min, :satuan, :barcode)
    ");
    foreach ($dummyBarang as $b) {
        $stmtBarang->execute([
            ':nama'    => $b[0],
            ':modal'   => $b[1],
            ':jual'    => $b[2],
            ':stok'    => $b[3],
            ':stok_min'=> $b[4],
            ':satuan'  => $b[5],
            ':barcode' => $b[6],
        ]);
    }

    // Dummy transaksi
    $dummyTransaksi = [
        ['2026-03-30 08:15:00', [[1,2],[5,3],[10,2]]],
        ['2026-03-30 10:30:00', [[4,5],[8,3]]],
        ['2026-03-30 14:45:00', [[2,1],[3,2],[6,1]]],
        ['2026-03-31 09:00:00', [[7,2],[9,1],[14,3]]],
        ['2026-03-31 11:20:00', [[11,1],[12,1],[15,1]]],
    ];

    $stmtTrx = $pdo->prepare("INSERT INTO transaksi (tanggal, total_harga, bayar, kembalian) VALUES (:tgl, :total, :bayar, :kembali)");
    $stmtDetail = $pdo->prepare("
        INSERT INTO detail_transaksi (transaksi_id, barang_id, nama_barang, harga_jual, jumlah, subtotal)
        VALUES (:trx_id, :brg_id, :nama, :harga, :jml, :sub)
    ");

    foreach ($dummyTransaksi as $trx) {
        $tanggal = $trx[0];
        $items   = $trx[1];
        $total   = 0;

        foreach ($items as $item) {
            $idx = $item[0] - 1;
            $total += $dummyBarang[$idx][2] * $item[1];
        }

        $bayar   = ceil($total / 5000) * 5000;
        $kembali = $bayar - $total;

        $stmtTrx->execute([':tgl' => $tanggal, ':total' => $total, ':bayar' => $bayar, ':kembali' => $kembali]);
        $trxId = $pdo->lastInsertId();

        foreach ($items as $item) {
            $idx    = $item[0] - 1;
            $jumlah = $item[1];
            $harga  = $dummyBarang[$idx][2];
            $stmtDetail->execute([
                ':trx_id' => $trxId,
                ':brg_id' => $item[0],
                ':nama'   => $dummyBarang[$idx][0],
                ':harga'  => $harga,
                ':jml'    => $jumlah,
                ':sub'    => $harga * $jumlah,
            ]);
        }
    }

    // Tandai mode demo
    $pdo->exec("INSERT OR REPLACE INTO pengaturan (kunci, nilai) VALUES ('demo_mode', '1')");
}
