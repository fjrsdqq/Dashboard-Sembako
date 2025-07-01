<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$nama_kategori = trim($_POST['nama_kategori'] ?? '');

if ($nama_kategori !== '') {
    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);
    if ($stmt->execute()) {
        echo "Kategori berhasil ditambahkan.";
    } else {
        http_response_code(500);
        echo "Gagal menyimpan: " . $stmt->error;
    }
} else {
    http_response_code(400);
    echo "Nama kategori tidak boleh kosong.";
}
?>
