<?php

require_once __DIR__ . '/../config/helpers.php';

function getAllSatuanKustom(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT * FROM satuan_kustom ORDER BY nama ASC");
    return $stmt ? $stmt->fetchAll() : [];
}

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

                $uploadDir = getUploadDir();

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
                $uploadDir = getUploadDir();
                if ($logoLama && file_exists($uploadDir . $logoLama)) {
                    unlink($uploadDir . $logoLama);
                }
                $stmt = $pdo->prepare("INSERT OR REPLACE INTO pengaturan (kunci, nilai) VALUES ('logo_toko', '')");
                $stmt->execute();
                return 'success|Logo berhasil dihapus.';

            case 'reset_database':
                $pdo->exec("PRAGMA foreign_keys = OFF");
                $pdo->exec("DELETE FROM detail_transaksi");
                $pdo->exec("DELETE FROM transaksi");
                $pdo->exec("DELETE FROM barang");
                $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('barang','transaksi','detail_transaksi')");
                $pdo->exec("DELETE FROM pengaturan WHERE kunci = 'demo_mode'");
                $pdo->exec("PRAGMA foreign_keys = ON");
                return 'success|Database berhasil direset. Semua data barang dan transaksi dihapus.';

            case 'isi_data_demo':
                $pdo->exec("PRAGMA foreign_keys = OFF");
                $pdo->exec("DELETE FROM detail_transaksi");
                $pdo->exec("DELETE FROM transaksi");
                $pdo->exec("DELETE FROM barang");
                $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('barang','transaksi','detail_transaksi')");
                $pdo->exec("PRAGMA foreign_keys = ON");
                require_once __DIR__ . '/../config/init_db.php';
                isiDataDemo($pdo);
                return 'success|Data demo berhasil diisi. Barang dan transaksi contoh sudah ditambahkan.';

            case 'tambah_satuan':
                $nama = strtolower(trim($_POST['nama_satuan'] ?? ''));
                if ($nama === '') {
                    return 'danger|Nama satuan tidak boleh kosong.';
                }
                $stmt = $pdo->prepare("INSERT OR IGNORE INTO satuan_kustom (nama) VALUES (:nama)");
                $stmt->execute([':nama' => $nama]);
                if ($stmt->rowCount() === 0) {
                    return 'warning|Satuan "' . $nama . '" sudah ada.';
                }
                return 'success|Satuan "' . $nama . '" berhasil ditambahkan.';

            case 'hapus_satuan':
                $id = (int) ($_POST['satuan_id'] ?? 0);
                if ($id <= 0) {
                    return 'danger|Data tidak valid.';
                }
                $stmt = $pdo->prepare("DELETE FROM satuan_kustom WHERE id = ?");
                $stmt->execute([$id]);
                return 'success|Satuan berhasil dihapus.';

            default:
                return 'danger|Aksi tidak dikenali.';
        }
    } catch (Exception $e) {
        return 'danger|Terjadi kesalahan: ' . $e->getMessage();
    }
}
