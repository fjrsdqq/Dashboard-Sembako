<?php
include "config.php";

$result = $conn->query("
    SELECT pj.id_penjualan, pj.tanggal, pj.jumlah, p.nama_produk, p.harga, k.nama_kategori
    FROM penjualan pj
    JOIN produk p ON pj.id_produk = p.id_produk
    LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
    ORDER BY pj.tanggal DESC
");

echo "<table border='1' cellpadding='10' cellspacing='0' width='100%'>
<tr style='background:#f0f0f0'>
    <th>No</th>
    <th>Produk</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>";

$no = 1;
while ($row = $result->fetch_assoc()) {
    $total = $row['harga'] * $row['jumlah'];
    $kategori = $row['nama_kategori'] ?: '-';
    echo "<tr>
        <td>{$no}</td>
        <td>{$row['nama_produk']}</td>
        <td>{$kategori}</td>
        <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
        <td>{$row['jumlah']}</td>
        <td>Rp " . number_format($total, 0, ',', '.') . "</td>
        <td>{$row['tanggal']}</td>
        <td>
            <a href='javascript:hapusPenjualan({$row['id_penjualan']})'>Hapus</a>
        </td>
    </tr>";
    $no++;
}
echo "</table>";
