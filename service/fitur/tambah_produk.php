<?php
session_start();
include '../nav/header.php';
include '../koneksi/database.php';
?>
<?php
// Proses simpan
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $id_kategori = $_POST['id_kategori'];
    $Stok= $_POST['Stok'];

    $stmt = $db->prepare("INSERT INTO produk(nama, harga, id_kategori, Stok) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdii", $nama, $harga, $id_kategori, $Stok);
    $stmt->execute();
    $_SESSION['notif_produk'] = "Produk berhasil ditambahkan.";
    $_SESSION['notif_type_produk'] = "success";
    header("Location: ../menu/produk.php");
    exit();
}

// Ambil kategori
$kategori = $db->query("SELECT * FROM kategori");
?>
<form method="POST">
    <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="0.01" name="harga" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="Stok" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <select name="id_kategori" class="form-control" required>
            <option value="">Pilih Kategori</option>
            <?php while($k = $kategori->fetch_assoc()): ?>
                <option value="<?= $k['id_kategori']; ?>"><?= htmlspecialchars($k['nama']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <button class="btn btn-success" type="submit" name="simpan">Simpan</button>
    <a href="../menu/produk.php" class="btn btn-secondary">Kembali</a>
</form>

<?php include '../nav/footer.php'; ?>
