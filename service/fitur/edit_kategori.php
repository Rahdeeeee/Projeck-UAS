<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';

$id = intval($_GET['id']);
$error = "";

// Ambil data lama
$stmt = $db->prepare("SELECT * FROM kategori WHERE id_kategori=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    $_SESSION['notif'] = "Kategori tidak ditemukan.";
    $_SESSION['notif_type'] = "danger";
    header("Location: ../menu/kategori.php");
    exit();
}

// Proses update
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama']);

    if ($nama != "") {
        $stmt = $db->prepare("UPDATE kategori SET nama=? WHERE id_kategori=?");
        $stmt->bind_param("si", $nama, $id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['notif'] = "Kategori berhasil diupdate.";
        $_SESSION['notif_type'] = "success";
        header("Location: ../menu/kategori.php");
        exit();
    } else {
        $error = "Nama kategori tidak boleh kosong.";
    }
}
?>

<h3>Edit Kategori</h3>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Nama Kategori</label>
        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($data['nama']); ?>">
    </div>
    <button class="btn btn-success" type="submit" name="update">Update</button>
    <a href="../menu/kategori.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../nav/footer.php'; ?>
