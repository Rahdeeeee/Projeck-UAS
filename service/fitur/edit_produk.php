<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';
?>

<h3>Edit Produk</h3>

<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['notif_produk'] = "ID produk tidak valid.";
    $_SESSION['notif_type_produk'] = "danger";
    header("Location: ../menu/produk.php");
    exit();
}

// Ambil data produk
$stmt = $db->prepare("SELECT * FROM produk WHERE id_produk=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

if (!$produk) {
    $_SESSION['notif_produk'] = "Produk tidak ditemukan.";
    $_SESSION['notif_type_produk'] = "danger";
    header("Location: ../menu/produk.php");
    exit();
}

// Ambil kategori
$kategori = $db->query("SELECT * FROM kategori");

// Proses update
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $id_kategori = $_POST['id_kategori'];

    $stmt = $db->prepare("UPDATE produk SET nama=?, harga=?, id_kategori=? WHERE id_produk=?");
    $stmt->bind_param("sdii", $nama, $harga, $id_kategori, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['notif_produk'] = "Produk berhasil diupdate.";
    $_SESSION['notif_type_produk'] = "success";
    header("Location: ../menu/produk.php");
    exit();
}
?>

<form method="POST">
    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama']); ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="0.01" name="harga" value="<?= htmlspecialchars($produk['harga']); ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <select name="id_kategori" class="form-control" required>
            <?php while($k = $kategori->fetch_assoc()): ?>
                <option value="<?= $k['id_kategori']; ?>" <?= $produk['id_kategori']==$k['id_kategori'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($k['nama']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <button class="btn btn-primary" type="submit" name="update">Update</button>
    <a href="../menu/produk.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../nav/footer.php'; ?>
