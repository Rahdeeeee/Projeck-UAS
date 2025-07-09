<?php
session_start();
include '../koneksi/database.php';

// ADD TO CART
if ($_GET['action'] == 'add' && isset($_POST['id_produk'], $_POST['qty'])) {
    $id_produk = $_POST['id_produk'];
    $qty = $_POST['qty'];

    // Cek produk
    $stmt = $db->prepare("SELECT nama, harga, Stok FROM produk WHERE id_produk = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();

    if (!$produk) {
        $_SESSION['notif_transaksi'] = "Produk tidak ditemukan.";
        $_SESSION['notif_type_transaksi'] = "danger";
    } else if ($produk['Stok'] < $qty) {
        $_SESSION['notif_transaksi'] = "Stok tidak cukup.";
        $_SESSION['notif_type_transaksi'] = "danger";
    } else {
        // Tambah ke keranjang
        $_SESSION['keranjang'][] = [
            'id_produk' => $id_produk,
            'nama' => $produk['nama'],
            'harga' => $produk['harga'],
            'qty' => $qty
        ];
        $_SESSION['notif_transaksi'] = "Produk berhasil ditambahkan ke keranjang.";
        $_SESSION['notif_type_transaksi'] = "success";
    }
    header("Location: ../menu/transaksi.php");
    exit();
}

// SAVE TRANSACTION
if ($_GET['action'] == 'save' && !empty($_SESSION['keranjang'])) {
    $total = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total += $item['harga'] * $item['qty'];
    }

    // Insert transaksi
    $stmt = $db->prepare("INSERT INTO transaksi(total) VALUES (?)");
    $stmt->bind_param("d", $total);
    $stmt->execute();
    $id_transaksi = $db->insert_id;

    // Insert detail transaksi
    foreach ($_SESSION['keranjang'] as $item) {
        $subtotal = $item['harga'] * $item['qty'];
        $stmt = $db->prepare("INSERT INTO detail_transaksi(id_transaksi, id_produk, qty, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $id_transaksi, $item['id_produk'], $item['qty'], $item['harga'], $subtotal);
        $stmt->execute();

        // Update stok
        $stmt2 = $db->prepare("UPDATE produk SET Stok = Stok - ? WHERE id_produk = ?");
        $stmt2->bind_param("ii", $item['qty'], $item['id_produk']);
        $stmt2->execute();
    }

    $_SESSION['notif_transaksi'] = "Transaksi berhasil disimpan.";
    $_SESSION['notif_type_transaksi'] = "success";
    $_SESSION['id_transaksi_terakhir'] = $id_transaksi;
    unset($_SESSION['keranjang']); // kosongkan keranjang

    header("Location: ../menu/transaksi.php");
    exit();
}
?>
