<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$nama        = $_POST['nama_produk'] ?? '';
$harga       = $_POST['harga'] ?? 0;
$stok        = $_POST['stok'] ?? 0;
$id_kategori = $_POST['id_kategori'] ?? '';

if ($nama && $harga >= 0 && $stok >= 0 && $id_kategori) {
    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok, id_kategori) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $nama, $harga, $stok, $id_kategori);

    if ($stmt->execute()) {
        echo "Produk berhasil ditambahkan!";
    } else {
        http_response_code(500);
        echo "Gagal menyimpan produk: " . $stmt->error;
    }
} else {
    http_response_code(400);
    echo "Data tidak valid atau belum lengkap.";
}
?>
