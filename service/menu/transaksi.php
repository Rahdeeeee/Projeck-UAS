<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';

// Ambil produk
$produk = $db->query("SELECT * FROM produk");

// Notifikasi
$notif = $_SESSION['notif_transaksi'] ?? '';
$type = $_SESSION['notif_type_transaksi'] ?? 'success';
unset($_SESSION['notif_transaksi'], $_SESSION['notif_type_transaksi']);

$id_transaksi_terakhir = $_SESSION['id_transaksi_terakhir'] ?? null;

// Inisialisasi keranjang kalau belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Handle hapus item dari keranjang
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    unset($_SESSION['keranjang'][$_GET['hapus']]);
}
?>
<head>
    <link rel="stylesheet" href="../../css/transaksi.css">
</head>
<div class="container mt-4">
    <?php if ($notif): ?>
        <div class="alert alert-<?= htmlspecialchars($type); ?>">
            <?= htmlspecialchars($notif); ?>
        </div>
    <?php endif; ?>

    <!-- Form tambah ke keranjang -->
    <form method="POST" action="../fitur/proses_transaksi.php?action=add">
        <div class="mb-3">
            <label>Produk</label>
            <select name="id_produk" class="form-control" required>
                <option value="">Pilih Produk</option>
                <?php while($p = $produk->fetch_assoc()): ?>
                    <option value="<?= $p['id_produk']; ?>">
                        <?= htmlspecialchars($p['nama']); ?> - Stok: <?= $p['Stok']; ?> - Rp <?= number_format($p['harga']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="qty" class="form-control" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">+ Tambah ke Keranjang</button>
    </form>

    <hr>

    <h5>Keranjang</h5>
    <?php if (!empty($_SESSION['keranjang'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['keranjang'] as $key => $item):
                    $subtotal = $item['harga'] * $item['qty'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']); ?></td>
                    <td><?= $item['qty']; ?></td>
                    <td>Rp <?= number_format($item['harga'],2,',','.'); ?></td>
                    <td>Rp <?= number_format($subtotal,2,',','.'); ?></td>
                    <td>
                        <a href="transaksi.php?hapus=<?= $key; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th colspan="2">Rp <?= number_format($total,2,',','.'); ?></th>
                </tr>
            </tfoot>
        </table>
        <form method="POST" action="../fitur/proses_transaksi.php?action=save">
            <button type="submit" class="btn btn-success">Simpan Transaksi</button>

        </form>
        <?php if ($id_transaksi_terakhir): ?>
        <a href="../fitur/cetak_struk.php?id=<?= $id_transaksi_terakhir; ?>" target="_blank" class="btn btn-primary">Cetak Struk</a>
        <?php endif; ?>
    <?php else: ?>
        <p>Keranjang masih kosong.</p>
    <?php endif; ?>
</div>
<?php include '../nav/footer.php'; ?>