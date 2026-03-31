<?php

require_once __DIR__ . '/../config/helpers.php';

function handlePengaturanAction(PDO $pdo): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }

    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'simpan_umum':
                $fields = ['nama_toko', 'alamat_toko', 'telepon_toko', 'footer_struk', 'warna_header'];
                $stmt = $pdo->prepare("INSERT OR REPLACE INTO pengaturan (kunci, nilai) VALUES (:k, :v)");
                foreach ($fields as $f) {
                    $stmt->execute([':k' => $f, ':v' => trim($_POST[$f] ?? '')]);
                }
                return 'success|Pengaturan berhasil disimpan.';

            case 'upload_logo':
                if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                    return 'danger|Gagal mengupload file logo.';
                }

                $file = $_FILES['logo'];
                $allowed = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    return 'danger|Format file tidak didukung. Gunakan PNG, JPG, GIF, atau WebP.';
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    return 'danger|Ukuran file maksimal 2MB.';
                }

                $uploadDir = __DIR__ . '/../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Hapus logo lama
                $logoLama = getPengaturan($pdo, 'logo_toko');
                if ($logoLama && file_exists($uploadDir . $logoLama)) {
                    unlink($uploadDir . $logoLama);
                }

                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'logo_' . time() . '.' . $ext;
                move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

                $stmt = $pdo->prepare("INSERT OR REPLACE INTO pengaturan (kunci, nilai) VALUES ('logo_toko', :v)");
                $stmt->execute([':v' => $filename]);

                return 'success|Logo berhasil diupload.';

            case 'hapus_logo':
                $logoLama = getPengaturan($pdo, 'logo_toko');
                $uploadDir = __DIR__ . '/../public/uploads/';
                if ($logoLama && file_exists($uploadDir . $logoLama)) {
                    unlink($uploadDir . $logoLama);
                }
                $stmt = $pdo->prepare("INSERT OR REPLACE INTO pengaturan (kunci, nilai) VALUES ('logo_toko', '')");
                $stmt->execute();
                return 'success|Logo berhasil dihapus.';

            default:
                return 'danger|Aksi tidak dikenali.';
        }
    } catch (Exception $e) {
        return 'danger|Terjadi kesalahan: ' . $e->getMessage();
    }
}
