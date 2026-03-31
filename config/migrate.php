<?php

function migrateDatabase(PDO $pdo): void
{
    // Cek kolom yang ada di tabel barang
    $cols = [];
    $stmt = $pdo->query("PRAGMA table_info(barang)");
    foreach ($stmt->fetchAll() as $c) {
        $cols[] = $c['name'];
    }

    // Migrasi: tambah kolom stok_minimal
    if (!in_array('stok_minimal', $cols)) {
        $pdo->exec("ALTER TABLE barang ADD COLUMN stok_minimal INTEGER NOT NULL DEFAULT 5");
    }

    // Migrasi: buat tabel satuan_kustom
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS satuan_kustom (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama TEXT NOT NULL UNIQUE
        )
    ");
}
