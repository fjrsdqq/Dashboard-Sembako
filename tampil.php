<?php include "config.php"; ?>

<h2>Data Penjualan</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Tanggal</th>
    </tr>

    <?php
    $no = 1;
    $query = "
        SELECT p.id_penjualan, pr.nama_produk, pr.harga, p.jumlah, p.tanggal
        FROM penjualan p
        JOIN produk pr ON p.id_produk = pr.id_produk
        ORDER BY p.tanggal DESC
    ";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $total = $row['harga'] * $row['jumlah'];
        echo "<tr>
            <td>$no</td>
            <td>{$row['nama_produk']}</td>
            <td>{$row['harga']}</td>
            <td>{$row['jumlah']}</td>
            <td>$total</td>
            <td>{$row['tanggal']}</td>
        </tr>";
        $no++;
    }
    ?>
</table>
