<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Produk</title>
    <style>
        input[type="text"] {
            padding: 6px;
            width: 250px;
            margin-bottom: 15px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #888;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Data Produk</h2>

<input type="text" id="search" placeholder="Cari nama produk...">

<!-- Bagian hasil tabel -->
<div id="tabel-produk">
    <?php
    // Query awal (semua data)
    $query = "
        SELECT p.*, k.nama_kategori 
        FROM produk p 
        LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
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
    ?>
</div>

<br><a href='index.php'>← Kembali ke Dashboard</a>

<!-- jQuery untuk AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var keyword = $(this).val();
            $.ajax({
                url: 'cari_produk.php',
                type: 'GET',
                data: { keyword: keyword },
                success: function(data) {
                    $('#tabel-produk').html(data);
                }
            });
        });
    });
</script>

</body>
</html>
