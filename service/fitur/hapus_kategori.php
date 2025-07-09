<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';

$id = intval($_GET['id']);

// Cek data
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

// Jika user sudah konfirmasi
if (isset($_POST['konfirmasi'])) {
    if ($_POST['konfirmasi'] == 'iya') {
        $stmt = $db->prepare("DELETE FROM kategori WHERE id_kategori=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['notif'] = "Kategori berhasil dihapus.";
        $_SESSION['notif_type'] = "success";
    } else {
        $_SESSION['notif'] = "Penghapusan dibatalkan.";
        $_SESSION['notif_type'] = "info";
    }
    header("Location: ../menu/kategori.php");
    exit();
}
?>

<h3>Hapus Kategori</h3>
<p>Apakah Anda yakin ingin menghapus kategori: <strong><?= htmlspecialchars($data['nama']); ?></strong>?</p>

<form method="POST">
    <button class="btn btn-danger" type="submit" name="konfirmasi" value="iya">Iya, Hapus</button>
    <button class="btn btn-secondary" type="submit" name="konfirmasi" value="tidak">Tidak</button>
</form>

<?php include '../nav/footer.php'; ?>
