<?php
include "config.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_penjualan.xls");

echo "<table border='1'>";
echo "<tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total</th></tr>";

$result = $conn->query("
    SELECT pj.tanggal, p.nama_produk, pj.jumlah, (p.harga * pj.jumlah) AS total
    FROM penjualan pj
    JOIN produk p ON pj.id_produk = p.id_produk
    ORDER BY pj.tanggal DESC
");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['tanggal']}</td>
            <td>{$row['nama_produk']}</td>
            <td>{$row['jumlah']}</td>
            <td>{$row['total']}</td>
        </tr>";
}
echo "</table>";
?>
