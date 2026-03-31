<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$pdo = getConnection();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'search_barang':
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 1) {
            echo json_encode([]);
            break;
        }
        $stmt = $pdo->prepare("
            SELECT id, nama, harga_jual, stok, satuan, barcode
            FROM barang
            WHERE nama LIKE :q OR barcode LIKE :q
            ORDER BY nama ASC
            LIMIT 20
        ");
        $stmt->execute([':q' => "%{$q}%"]);
        $results = $stmt->fetchAll();

        $data = [];
        foreach ($results as $r) {
            $data[] = [
                'id'         => $r['id'],
                'text'       => $r['nama'] . ' — Rp ' . number_format($r['harga_jual'], 0, ',', '.') . ' (stok: ' . $r['stok'] . ')',
                'nama'       => $r['nama'],
                'harga_jual' => $r['harga_jual'],
                'stok'       => $r['stok'],
                'satuan'     => $r['satuan'],
                'barcode'    => $r['barcode'],
            ];
        }
        echo json_encode(['results' => $data]);
        break;

    case 'simpan_transaksi':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];
        $bayar = (float) ($input['bayar'] ?? 0);

        if (empty($items)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang kosong.']);
            break;
        }

        $totalHarga = 0;
        foreach ($items as $item) {
            $totalHarga += (float) $item['harga_jual'] * (int) $item['jumlah'];
        }

        if ($bayar < $totalHarga) {
            echo json_encode(['success' => false, 'message' => 'Uang bayar kurang.']);
            break;
        }

        $kembalian = $bayar - $totalHarga;

        try {
            $pdo->beginTransaction();

            // Insert transaksi
            $stmt = $pdo->prepare("
                INSERT INTO transaksi (total_harga, bayar, kembalian)
                VALUES (:total, :bayar, :kembalian)
            ");
            $stmt->execute([
                ':total'     => $totalHarga,
                ':bayar'     => $bayar,
                ':kembalian' => $kembalian,
            ]);
            $transaksiId = $pdo->lastInsertId();

            // Insert detail & kurangi stok
            $stmtDetail = $pdo->prepare("
                INSERT INTO detail_transaksi (transaksi_id, barang_id, nama_barang, harga_jual, jumlah, subtotal)
                VALUES (:tid, :bid, :nama, :harga, :jumlah, :subtotal)
            ");
            $stmtStok = $pdo->prepare("
                UPDATE barang SET stok = stok - :jumlah, updated_at = datetime('now','localtime')
                WHERE id = :id AND stok >= :jumlah
            ");

            foreach ($items as $item) {
                $jumlah = (int) $item['jumlah'];
                $subtotal = (float) $item['harga_jual'] * $jumlah;

                $stmtDetail->execute([
                    ':tid'     => $transaksiId,
                    ':bid'     => $item['id'],
                    ':nama'    => $item['nama'],
                    ':harga'   => $item['harga_jual'],
                    ':jumlah'  => $jumlah,
                    ':subtotal'=> $subtotal,
                ]);

                $stmtStok->execute([
                    ':jumlah' => $jumlah,
                    ':id'     => $item['id'],
                ]);

                if ($stmtStok->rowCount() === 0) {
                    $pdo->rollBack();
                    echo json_encode([
                        'success' => false,
                        'message' => "Stok \"{$item['nama']}\" tidak mencukupi."
                    ]);
                    exit;
                }
            }

            $pdo->commit();

            echo json_encode([
                'success'    => true,
                'message'    => 'Transaksi berhasil disimpan.',
                'data'       => [
                    'id'         => $transaksiId,
                    'total'      => $totalHarga,
                    'bayar'      => $bayar,
                    'kembalian'  => $kembalian,
                ],
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
        break;

    case 'reset_transaksi':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
        }
        try {
            $pdo->exec("DELETE FROM detail_transaksi");
            $pdo->exec("DELETE FROM transaksi");
            $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('transaksi','detail_transaksi')");
            echo json_encode(['success' => true, 'message' => 'Semua data transaksi berhasil dihapus.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
        break;

    case 'reset_all':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
        }
        try {
            $pdo->exec("DELETE FROM detail_transaksi");
            $pdo->exec("DELETE FROM transaksi");
            $pdo->exec("DELETE FROM barang");
            $pdo->exec("DELETE FROM pengaturan");
            $pdo->exec("DELETE FROM sqlite_sequence");
            // Re-insert default pengaturan
            require_once __DIR__ . '/../config/init_db.php';
            initDatabase($pdo);
            echo json_encode(['success' => true, 'message' => 'Seluruh database berhasil direset.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
        break;

    case 'tambah_barang_manual':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = trim($input['nama'] ?? '');
        $harga = (float) ($input['harga'] ?? 0);

        if ($nama === '' || $harga <= 0) {
            echo json_encode(['success' => false, 'message' => 'Nama dan harga wajib diisi.']);
            break;
        }

        // Cek apakah barang dengan nama yang sama sudah ada
        $stmt = $pdo->prepare("SELECT id, nama, harga_jual, stok, satuan, barcode FROM barang WHERE LOWER(nama) = LOWER(?)");
        $stmt->execute([$nama]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Barang sudah ada, langsung return datanya
            echo json_encode([
                'success' => true,
                'barang' => $existing,
                'new' => false,
            ]);
        } else {
            // Buat barang baru
            require_once __DIR__ . '/../controllers/BarangController.php';
            $barcode = generateBarcode($pdo);
            $stmt = $pdo->prepare("
                INSERT INTO barang (nama, harga_modal, harga_jual, stok, stok_minimal, satuan, barcode)
                VALUES (:nama, 0, :harga, 9999, 5, 'pcs', :barcode)
            ");
            $stmt->execute([':nama' => $nama, ':harga' => $harga, ':barcode' => $barcode]);
            $newId = $pdo->lastInsertId();

            echo json_encode([
                'success' => true,
                'barang' => [
                    'id' => $newId,
                    'nama' => $nama,
                    'harga_jual' => $harga,
                    'stok' => 9999,
                    'satuan' => 'pcs',
                    'barcode' => $barcode,
                ],
                'new' => true,
            ]);
        }
        break;

    case 'tambah_satuan':
        $nama = strtolower(trim($_GET['nama'] ?? ''));
        if ($nama !== '') {
            $stmt = $pdo->prepare("INSERT OR IGNORE INTO satuan_kustom (nama) VALUES (:nama)");
            $stmt->execute([':nama' => $nama]);
        }
        echo json_encode(['success' => true]);
        break;

    case 'hapus_satuan':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false]);
            break;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $nama = strtolower(trim($input['nama'] ?? ''));
        $gantiDengan = strtolower(trim($input['ganti_dengan'] ?? 'pcs'));
        if ($nama === '') {
            echo json_encode(['success' => false, 'message' => 'Nama satuan kosong.']);
            break;
        }
        // Ganti satuan di semua barang yang pakai satuan ini
        $stmt = $pdo->prepare("UPDATE barang SET satuan = :ganti WHERE satuan = :lama");
        $stmt->execute([':ganti' => $gantiDengan, ':lama' => $nama]);
        $affected = $stmt->rowCount();
        // Hapus dari tabel satuan_kustom
        $stmt = $pdo->prepare("DELETE FROM satuan_kustom WHERE nama = ?");
        $stmt->execute([$nama]);
        echo json_encode(['success' => true, 'affected' => $affected]);
        break;

    case 'cek_satuan_dipakai':
        $nama = strtolower(trim($_GET['nama'] ?? ''));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE satuan = ?");
        $stmt->execute([$nama]);
        $count = (int) $stmt->fetchColumn();
        echo json_encode(['count' => $count]);
        break;

    case 'check_barcode':
        $bc = trim($_GET['barcode'] ?? '');
        $excludeId = (int) ($_GET['exclude_id'] ?? 0);
        if ($bc === '') {
            echo json_encode(['exists' => false]);
            break;
        }
        if ($excludeId > 0) {
            $stmt = $pdo->prepare("SELECT id, nama FROM barang WHERE barcode = ? AND id != ?");
            $stmt->execute([$bc, $excludeId]);
        } else {
            $stmt = $pdo->prepare("SELECT id, nama FROM barang WHERE barcode = ?");
            $stmt->execute([$bc]);
        }
        $found = $stmt->fetch();
        if ($found) {
            echo json_encode(['exists' => true, 'message' => 'Barcode sudah dipakai oleh: ' . $found['nama']]);
        } else {
            echo json_encode(['exists' => false]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenali.']);
}
