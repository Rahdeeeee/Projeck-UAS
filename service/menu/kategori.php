<?php
session_start();

include '../nav/header.php';
include '../koneksi/database.php';

$notif = "";
$notif_type = "";

if (isset($_SESSION['notif'])) {
    $notif = $_SESSION['notif'];
    $notif_type = $_SESSION['notif_type'] ?? 'success';
    unset($_SESSION['notif'], $_SESSION['notif_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
    <link rel="stylesheet" href="../../css/kategori.css">
</head>
</html>

<a href="../fitur/tambah_kategori.php" class="btn btn-primary mb-3">+ Tambah Kategori</a>
<?php if (!empty($notif)): ?>
<div class="alert alert-<?= htmlspecialchars($notif_type); ?>">
    <?= htmlspecialchars($notif); ?>
</div>
<?php endif; ?>
<?php
$result = $db->query("SELECT * FROM kategori ORDER BY id_kategori DESC");
?>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td>
                <a href="../fitur/edit_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="../fitur/hapus_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-sm btn-danger">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../nav/footer.php'; ?>
