<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

$pdo = getConnection();
$settings = getSemuaPengaturan($pdo);

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    die('ID transaksi tidak valid.');
}

// Ambil data transaksi
$stmt = $pdo->prepare("SELECT * FROM transaksi WHERE id = ?");
$stmt->execute([$id]);
$trx = $stmt->fetch();

if (!$trx) {
    die('Transaksi tidak ditemukan.');
}

// Ambil detail
$stmt = $pdo->prepare("SELECT * FROM detail_transaksi WHERE transaksi_id = ? ORDER BY id ASC");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

$tanggal = date('d/m/Y H:i', strtotime($trx['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $trx['id'] ?></title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Thermal 58mm = ~219px at 96dpi, tapi kita pakai mm untuk print */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            width: 48mm;
            margin: 0 auto;
            padding: 2mm;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }
        .bold        { font-weight: bold; }
        .fs-large    { font-size: 14px; }

        .divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        .header {
            text-align: center;
            margin-bottom: 2px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .header p {
            font-size: 10px;
            line-height: 1.3;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .item-row {
            margin-bottom: 2px;
        }

        .item-name {
            font-size: 12px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding-left: 2mm;
        }

        .total-section .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .total-section .grand-total {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 4px;
        }

        /* Print styles */
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            html, body {
                width: 58mm;
                margin: 0;
                padding: 1mm;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Screen preview styling */
        @media screen {
            html {
                background: #e0e0e0;
            }

            body {
                background: #fff;
                margin: 20px auto;
                padding: 4mm;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                min-height: 100px;
            }

            .no-print {
                width: 58mm;
                margin: 10px auto 20px;
                text-align: center;
            }

            .no-print button {
                padding: 8px 24px;
                font-size: 14px;
                cursor: pointer;
                background: #198754;
                color: #fff;
                border: none;
                border-radius: 4px;
                margin: 0 4px;
            }

            .no-print button:hover {
                background: #157347;
            }

            .no-print .btn-close-struk {
                background: #6c757d;
            }
        }
    </style>
</head>
<body>

    <!-- Header Toko -->
    <div class="header">
        <?php if (!empty($settings['logo_toko'])): ?>
            <img src="uploads/<?= htmlspecialchars($settings['logo_toko']) ?>"
                 alt="Logo" style="max-height: 40px; max-width: 100px; margin-bottom: 2px;"><br>
        <?php endif; ?>
        <h1><?= htmlspecialchars(strtoupper($settings['nama_toko'])) ?></h1>
        <p><?= htmlspecialchars($settings['alamat_toko']) ?><br>Telp: <?= htmlspecialchars($settings['telepon_toko']) ?></p>
    </div>

    <hr class="divider">

    <!-- Info Transaksi -->
    <div class="info-row">
        <span>No: #<?= str_pad($trx['id'], 4, '0', STR_PAD_LEFT) ?></span>
        <span><?= $tanggal ?></span>
    </div>

    <hr class="divider">

    <!-- Daftar Item -->
    <?php foreach ($items as $item): ?>
    <div class="item-row">
        <div class="item-name"><?= htmlspecialchars($item['nama_barang']) ?></div>
        <div class="item-detail">
            <span><?= $item['jumlah'] ?> x <?= number_format($item['harga_jual'], 0, ',', '.') ?></span>
            <span><?= number_format($item['subtotal'], 0, ',', '.') ?></span>
        </div>
    </div>
    <?php endforeach; ?>

    <hr class="divider">

    <!-- Total -->
    <div class="total-section">
        <div class="grand-total">
            <span>TOTAL</span>
            <span>Rp <?= number_format($trx['total_harga'], 0, ',', '.') ?></span>
        </div>
        <div class="total-row">
            <span>Bayar</span>
            <span>Rp <?= number_format($trx['bayar'], 0, ',', '.') ?></span>
        </div>
        <div class="total-row">
            <span>Kembali</span>
            <span>Rp <?= number_format($trx['kembalian'], 0, ',', '.') ?></span>
        </div>
    </div>

    <hr class="divider">

    <!-- Footer -->
    <div class="footer">
        <p class="bold">Terima Kasih!</p>
        <p><?= nl2br(htmlspecialchars($settings['footer_struk'])) ?></p>
    </div>

    <!-- Tombol (tidak tampil saat print) -->
    <div class="no-print">
        <button onclick="window.print()">Cetak Ulang</button>
        <button class="btn-close-struk" onclick="window.close()">Tutup</button>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
