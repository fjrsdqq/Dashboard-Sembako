<?php
include "config.php";

$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';

$query = "
    SELECT p.*, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
    WHERE p.nama_produk LIKE '%$keyword%'
    ORDER BY p.id_produk DESC
";

$result = $conn->query($query);

echo "<table>";
echo "<tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stok</th>
      </tr>";

$no = 1;
while ($row = $result->fetch_assoc()) {
    $nama_produk   = htmlspecialchars($row['nama_produk']);
    $nama_kategori = htmlspecialchars($row['nama_kategori'] ?? '-');
    $harga         = "Rp " . number_format($row['harga'], 0, ',', '.');
    $stok          = $row['stok'];

    echo "<tr>
            <td>{$no}</td>
            <td>{$nama_produk}</td>
            <td>{$nama_kategori}</td>
            <td>{$harga}</td>
            <td>{$stok}</td>
          </tr>";
    $no++;
}
echo "</table>";
