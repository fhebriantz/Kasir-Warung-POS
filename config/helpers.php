<?php

/**
 * Ambil semua pengaturan dari database sebagai array key-value.
 */
function getSemuaPengaturan(PDO $pdo): array
{
    // Pastikan tabel pengaturan ada (untuk DB lama yang belum punya tabel ini)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pengaturan (
            kunci TEXT PRIMARY KEY,
            nilai TEXT NOT NULL DEFAULT ''
        )
    ");

    $defaults = [
        'nama_toko'    => 'Kasir Warung',
        'alamat_toko'  => 'Jl. Contoh Alamat No. 123',
        'telepon_toko' => '08xx-xxxx-xxxx',
        'footer_struk' => 'Barang yang sudah dibeli tidak dapat dikembalikan',
        'warna_header' => '#198754',
        'logo_toko'    => '',
    ];

    $stmt = $pdo->query("SELECT kunci, nilai FROM pengaturan");
    $rows = $stmt->fetchAll();

    $settings = $defaults;
    foreach ($rows as $row) {
        $settings[$row['kunci']] = $row['nilai'];
    }

    return $settings;
}

/**
 * Path folder uploads (kompatibel development & PHP Desktop).
 * Development: public/uploads/
 * PHP Desktop: www/uploads/
 */
function getUploadDir(): string
{
    // Cek dari index.php (public/ atau www/)
    $fromPublic = __DIR__ . '/../public/uploads/';
    $fromWww = __DIR__ . '/../uploads/';

    if (is_dir(dirname($fromPublic))) {
        if (!is_dir($fromPublic)) mkdir($fromPublic, 0755, true);
        return $fromPublic;
    }

    if (!is_dir($fromWww)) mkdir($fromWww, 0755, true);
    return $fromWww;
}

/**
 * Ambil satu nilai pengaturan.
 */
function getPengaturan(PDO $pdo, string $kunci, string $default = ''): string
{
    $all = getSemuaPengaturan($pdo);
    return $all[$kunci] ?? $default;
}
