<?php
include __DIR__ . '/../nav/header.php';
include __DIR__ . '/../koneksi/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
</head>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3" style="background: linear-gradient(45deg, #4e73df, #224abe); color:white;">
            <div class="card-body">
                <h5>Total Kategori</h5>
                <?php
                $row = $db->query("SELECT COUNT(*) AS total FROM kategori")->fetch_assoc();
                echo "<p class='fs-4'>{$row['total']}</p>";
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5>Total Produk</h5>
                <?php
                $row = $db->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc();
                echo "<p class='fs-4'>{$row['total']}</p>";
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5>Total User</h5>
                <?php
                $row = $db->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc();
                echo "<p class='fs-4'>{$row['total']}</p>";
                ?>
            </div>
        </div>
    </div>
</div>

<div class="chart-card">
    <h5>Produk per Kategori</h5>
    <canvas id="produkChart" height="100"></canvas>
</div>



<?php
$data = $db->query("
    SELECT k.nama, COUNT(p.id_produk) as total 
    FROM kategori k 
    LEFT JOIN produk p ON k.id_kategori = p.id_kategori
    GROUP BY k.id_kategori
");
$labels = []; $totals = [];
while($d = $data->fetch_assoc()) {
    $labels[] = $d['nama'];
    $totals[] = $d['total'];
}
?>
<script>
const ctx = document.getElementById('produkChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            label: 'Jumlah Produk',
            data: <?= json_encode($totals); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, title: { display: true, text: 'Produk per Kategori' }},
        scales: { y: { beginAtZero: true, precision:0 } }
    }
});
</script>
<?php include __DIR__ . '/../nav/footer.php'; ?>
