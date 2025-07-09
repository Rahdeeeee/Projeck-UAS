<?php
// Mulai session hanya jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "Silakan login untuk mengakses dashboard.";
    $_SESSION['login_message_type'] = "danger";
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyKasir</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../css/header.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="logo">
        <img src="../../img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="../menu/dashboard.php"><i class="bi bi-house"></i> <span>Dashboard</span></a>
    <a href="../menu/kategori.php"><i class="bi bi-tags"></i> <span>Kategori</span></a>
    <a href="../menu/produk.php"><i class="bi bi-box"></i> <span>Produk</span></a>
    <a href="../menu/transaksi.php"><i class="bi bi-cart"></i> <span>Transaksi</span></a>
    <form action="../logout.php" method="post" style="margin-top:auto; padding:10px;">
        <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>
</div>

<div class="header">
    <button id="toggleDarkMode">🌓</button>
    <span class="header-title">Dashboard</span>
</div>

<div class="content">
<script>
document.getElementById('toggleDarkMode').onclick = function() {
    document.body.classList.toggle('dark');
};
</script>
