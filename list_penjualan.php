<?php
include "config.php";
echo "<h2>Data Penjualan</h2>";

$query = "SELECT 
            p.nama_produk, 
            pj.jumlah, 
            p.harga, 
            pj.tanggal, 
            k.nama_kategori
          FROM penjualan pj
          JOIN produk p ON pj.id_produk = p.id_produk
          LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
          ORDER BY pj.tanggal DESC";

$result = $conn->query($query);

echo "<table border='1' cellpadding='10'>";
echo "<tr>
        <th>No</th>
        <th>Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Tanggal</th>
      </tr>";

$no = 1;
while ($row = $result->fetch_assoc()) {
    $total = $row['harga'] * $row['jumlah'];
    echo "<tr>
            <td>{$no}</td>
            <td>" . htmlspecialchars($row['nama_produk']) . "</td>
            <td>" . htmlspecialchars($row['nama_kategori'] ?? '-') . "</td>
            <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
            <td>{$row['jumlah']}</td>
            <td>Rp " . number_format($total, 0, ',', '.') . "</td>
            <td>{$row['tanggal']}</td>
          </tr>";
    $no++;
}
echo "</table><br><a href='index.php'> ← Kembali ke Dashboard</a>";
