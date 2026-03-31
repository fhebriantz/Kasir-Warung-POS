<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

$pdo = getConnection();
$settings = getSemuaPengaturan($pdo);

$page = $_GET['page'] ?? 'dashboard';

$allowedPages = ['dashboard', 'kasir', 'barang', 'barcode', 'transaksi', 'laporan', 'pengaturan', 'bantuan'];
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

$viewMap = [
    'dashboard'   => 'kasir/kasir',
    'kasir'       => 'kasir/pos',
    'barang'      => 'barang/barang',
    'barcode'     => 'barcode/barcode',
    'transaksi'   => 'transaksi/transaksi',
    'laporan'     => 'laporan/laporan',
    'pengaturan'  => 'pengaturan/pengaturan',
    'bantuan'     => 'bantuan/bantuan',
];

include __DIR__ . '/../views/layouts/header.php';
include __DIR__ . "/../views/{$viewMap[$page]}.php";
include __DIR__ . '/../views/layouts/footer.php';
