<?php

function generateBarcode(PDO $pdo): string
{
    do {
        $code = 'KW' . date('ymd') . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE barcode = ?");
        $stmt->execute([$code]);
        $exists = (int) $stmt->fetchColumn() > 0;
    } while ($exists);
    return $code;
}

function getAllBarang(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM barang ORDER BY id DESC");
    return $stmt->fetchAll();
}

function getBarangById(PDO $pdo, int $id): array|false
{
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createBarang(PDO $pdo, array $data): bool
{
    $barcode = trim($data['barcode'] ?? '');
    if ($barcode === '') {
        $barcode = generateBarcode($pdo);
    }

    $stmt = $pdo->prepare("
        INSERT INTO barang (nama, harga_modal, harga_jual, stok, stok_minimal, satuan, barcode)
        VALUES (:nama, :harga_modal, :harga_jual, :stok, :stok_minimal, :satuan, :barcode)
    ");
    return $stmt->execute([
        ':nama'         => trim($data['nama']),
        ':harga_modal'  => (float) $data['harga_modal'],
        ':harga_jual'   => (float) $data['harga_jual'],
        ':stok'         => (int) $data['stok'],
        ':stok_minimal' => (int) ($data['stok_minimal'] ?? 5),
        ':satuan'       => trim($data['satuan']),
        ':barcode'      => $barcode,
    ]);
}

function updateBarang(PDO $pdo, int $id, array $data): bool
{
    $stmt = $pdo->prepare("
        UPDATE barang
        SET nama = :nama,
            harga_modal = :harga_modal,
            harga_jual = :harga_jual,
            stok = :stok,
            stok_minimal = :stok_minimal,
            satuan = :satuan,
            barcode = :barcode,
            updated_at = datetime('now','localtime')
        WHERE id = :id
    ");
    return $stmt->execute([
        ':id'           => $id,
        ':nama'         => trim($data['nama']),
        ':harga_modal'  => (float) $data['harga_modal'],
        ':harga_jual'   => (float) $data['harga_jual'],
        ':stok'         => (int) $data['stok'],
        ':stok_minimal' => (int) ($data['stok_minimal'] ?? 5),
        ':satuan'       => trim($data['satuan']),
        ':barcode'      => trim($data['barcode']) ?: null,
    ]);
}

function deleteBarang(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    return $stmt->execute([$id]);
}

function handleBarangAction(PDO $pdo): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create':
                if (empty(trim($_POST['nama'] ?? ''))) {
                    return 'danger|Nama barang wajib diisi.';
                }
                createBarang($pdo, $_POST);
                return 'success|Barang berhasil ditambahkan.';

            case 'update':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0 || empty(trim($_POST['nama'] ?? ''))) {
                    return 'danger|Data tidak valid.';
                }
                updateBarang($pdo, $id, $_POST);
                return 'success|Barang berhasil diperbarui.';

            case 'delete':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    return 'danger|Data tidak valid.';
                }
                deleteBarang($pdo, $id);
                return 'success|Barang berhasil dihapus.';

            default:
                return 'danger|Aksi tidak dikenali.';
        }
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
            return 'danger|Barcode sudah digunakan oleh barang lain.';
        }
        return 'danger|Terjadi kesalahan: ' . $e->getMessage();
    }
}
