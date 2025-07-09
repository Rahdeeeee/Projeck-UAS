<?php
session_start();
include '../nav/header.php'; 
include '../koneksi/database.php';
$notif_produk = "";
$notif_type_produk = "";

if (isset($_SESSION['notif_produk'])) {
    $notif_produk = $_SESSION['notif_produk'];
    $notif_type_produk = $_SESSION['notif_type_produk'] ?? 'success';
    unset($_SESSION['notif_produk'], $_SESSION['notif_type_produk']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="../../css/produk.css">
</head>

</html>
<a href="../fitur/tambah_produk.php" class="btn btn-primary mb-3">+ Tambah Produk</a>
<?php
$result = $db->query("SELECT p.*, k.nama AS kategori FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori");
?>
<?php if (!empty($notif_produk)): ?>
<div class="alert alert-<?= htmlspecialchars($notif_type_produk); ?>">
    <?= htmlspecialchars($notif_produk); ?>
</div>
<?php endif; ?>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td>Rp <?= number_format($row['harga']); ?></td>
            <td><?= htmlspecialchars($row['kategori']); ?></td>
            <td><?= htmlspecialchars($row['Stok']); ?></td>
            <td>
                <a href="../fitur/edit_produk.php?id=<?= $row['id_produk']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="../fitur/hapus_produk.php?id=<?= $row['id_produk']; ?>" class="btn btn-sm btn-danger">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../nav/footer.php'; ?>
