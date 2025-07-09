<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../koneksi/database.php';

// Pastikan id transaksi aman
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data transaksi
$transaksi = $db->query("SELECT * FROM transaksi WHERE id_transaksi = $id")->fetch_assoc();

// Cek jika tidak ada transaksi
if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Ambil detail transaksi
$detail = $db->query("SELECT dt.*, p.nama 
    FROM detail_transaksi dt 
    JOIN produk p ON dt.id_produk = p.id_produk 
    WHERE dt.id_transaksi = $id");

// Ambil nama kasir dari session
$kasir = $_SESSION['username'] ?? 'Kasir';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= htmlspecialchars($id); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/cetak_struk.css">
    <script>
        window.onload = function(){
            window.print();
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="info">
            <strong>MyKasir</strong><br>
            Jl. Mawar No. 123, Bali<br>
            Telp: 0812-3456-7890
        </div>
    </div>

    <h4>Struk Transaksi</h4>
    <p>
        No: <?= htmlspecialchars($transaksi['id_transaksi']); ?><br>
        Tanggal: <?= htmlspecialchars($transaksi['tanggal'] ?? date('Y-m-d H:i:s')); ?><br>
        Kasir: <?= htmlspecialchars($kasir); ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while($d = $detail->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($d['nama']); ?></td>
                <td><?= $d['qty']; ?></td>
                <td style="text-align:right;">Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p class="total">
        <strong>Total: Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></strong>
    </p>

    <div class="footer">
        ~ Terima kasih telah berbelanja ~<br>
        Barang yang sudah dibeli tidak dapat dikembalikan.
    </div>

    <button onclick="window.print()">Cetak Lagi</button>
</body>
</html>
