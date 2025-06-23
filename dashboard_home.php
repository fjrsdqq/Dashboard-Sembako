<?php
include "config.php";

$total_produk = $conn->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc()['total'];
$total_kategori = $conn->query("SELECT COUNT(*) AS total FROM kategori")->fetch_assoc()['total'];
$total_penjualan = $conn->query("SELECT COUNT(*) AS total FROM penjualan")->fetch_assoc()['total'];

$chart = $conn->query("
    SELECT DATE(tanggal) as tanggal, SUM(jumlah) as total
    FROM penjualan
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(tanggal)
");
$labels = [];
$values = [];
while ($row = $chart->fetch_assoc()) {
    $labels[] = $row['tanggal'];
    $values[] = $row['total'];
}

$recent = $conn->query("
    SELECT pj.tanggal, p.nama_produk, pj.jumlah, (p.harga * pj.jumlah) AS total
    FROM penjualan pj
    JOIN produk p ON pj.id_produk = p.id_produk
    ORDER BY pj.tanggal DESC LIMIT 5
");

$bulan = $conn->query("
    SELECT pj.tanggal, p.nama_produk, pj.jumlah, (p.harga * pj.jumlah) AS total
    FROM penjualan pj
    JOIN produk p ON pj.id_produk = p.id_produk
    WHERE pj.tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ORDER BY pj.tanggal DESC
");
?>

<div class="card-box">
    <div class="card produk">
        <h3>📦 Total Produk</h3>
        <p><?= $total_produk ?></p>
    </div>
    <div class="card kategori">
        <h3>📁 Total Kategori</h3>
        <p><?= $total_kategori ?></p>
    </div>
    <div class="card penjualan">
        <h3>🩾 Total Penjualan</h3>
        <p><?= $total_penjualan ?></p>
    </div>
</div>

<div id="search-results"></div>

<div id="dashboard-content">
    <h3>📊 Grafik Penjualan 7 Hari Terakhir</h3>
    <canvas id="chartPenjualan" height="100"></canvas>

    <h3 style="margin-top: 40px;">🕒 5 Transaksi Terbaru</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr style="background-color: #ecf0f1;">
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
        <?php while ($r = $recent->fetch_assoc()): ?>
            <tr>
                <td><?= $r['tanggal'] ?></td>
                <td><?= $r['nama_produk'] ?></td>
                <td><?= $r['jumlah'] ?></td>
                <td>Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3 style="margin-top: 40px;">🗓️ Penjualan 30 Hari Terakhir</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr style="background-color: #ecf0f1;">
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Total</th>
        </tr>
        <?php while ($row = $bulan->fetch_assoc()): ?>
            <tr>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['nama_produk'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="export_excel.php">📄 Export Semua Penjualan ke Excel</a>
</div>

<script>
    const ctx = document.getElementById('chartPenjualan').getContext('2d');
    const chartPenjualan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Jumlah Terjual',
                data: <?= json_encode($values) ?>,
                backgroundColor: '#3498db'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
