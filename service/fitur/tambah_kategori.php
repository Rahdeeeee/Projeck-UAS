<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';
?>
<?php
// Proses simpan
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);

    if ($nama != '') {
        // Simpan ke database
        $stmt = $db->prepare("INSERT INTO kategori(nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $stmt->close();

        // Redirect ke daftar kategori
        $_SESSION['notif'] = "Kategori berhasil ditambahkan.";
        $_SESSION['notif_type'] = "success";
        header("Location: ../menu/kategori.php");
        exit();
    } else {
        $_SESSION['notif'] = "Nama kategori tidak boleh kosong.";
        $_SESSION['notif_type'] = "info";
    }
}
?>

<h3>Tambah Kategori</h3>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Nama Kategori</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <button class="btn btn-success" type="submit" name="simpan">Simpan</button>
    <a href="../menu/kategori.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../nav/footer.php'; ?>
