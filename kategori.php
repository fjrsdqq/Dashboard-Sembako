<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    $nama_kategori = $_POST['nama_kategori'];

    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);

    if ($stmt->execute()) {
        echo "✅ Kategori berhasil ditambahkan!";
    } else {
        echo "❌ Gagal menambahkan kategori.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Kategori</title>
</head>
<body>
<h2>Input Kategori Baru</h2>
<form method="POST" action="">
    <label>Nama Kategori:</label><br>
    <input type="text" name="nama_kategori" required><br><br>
    <button type="submit" name="submit">Simpan Kategori</button>
</form>
<br>
<a href="produk.php">← Kembali ke Produk</a>
</body>
</html>
