<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';
?>

<h3>Hapus Produk</h3>

<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['notif_produk'] = "ID produk tidak valid.";
    $_SESSION['notif_type_produk'] = "danger";
    header("Location: ../menu/produk.php");
    exit();
}

// Ambil data produk
$stmt = $db->prepare("SELECT nama FROM produk WHERE id_produk=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

// Cek apakah produk ada
if (!$produk) {
    $_SESSION['notif_produk'] = "Produk tidak ditemukan.";
    $_SESSION['notif_type_produk'] = "danger";
    header("Location: ../menu/produk.php");
    exit();
}

// Kalau user klik tombol konfirmasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['konfirmasi'])) {
    if ($_POST['konfirmasi'] === 'iya') {
        $stmt = $db->prepare("DELETE FROM produk WHERE id_produk=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['notif_produk'] = "Produk berhasil dihapus.";
        $_SESSION['notif_type_produk'] = "success";
    } else {
        $_SESSION['notif_produk'] = "Penghapusan dibatalkan.";
        $_SESSION['notif_type_produk'] = "info";
    }
    header("Location: ../menu/produk.php");
    exit();
}
?>

<p>Apakah Anda yakin ingin menghapus produk <strong><?= htmlspecialchars($produk['nama']); ?></strong>?</p>
<form method="POST">
    <button class="btn btn-danger" type="submit" name="konfirmasi" value="iya">Iya, Hapus</button>
    <button class="btn btn-secondary" type="submit" name="konfirmasi" value="tidak">Tidak</button>
</form>

<?php include '../nav/footer.php'; ?>
