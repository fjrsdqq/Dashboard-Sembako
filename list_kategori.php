<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil data kategori dari database
$result = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kategori</title>
</head>
<body>

<h2>📂 Daftar Kategori</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Kategori</th>
    </tr>
    <?php
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>" . htmlspecialchars($row['nama_kategori']) . "</td>
              </tr>";
        $no++;
    }
    ?>
</table>

<br>
<a href="index.php">← Kembali ke Dashboard</a>

</body>
</html>
