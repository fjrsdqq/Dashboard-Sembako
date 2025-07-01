<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_produk = $_POST['id_produk'];
$jumlah = $_POST['jumlah'];

// Cek stok dulu
$cek = $conn->query("SELECT stok FROM produk WHERE id_produk = $id_produk")->fetch_assoc();
if (!$cek) {
    http_response_code(400);
    echo "Produk tidak ditemukan.";
    exit;
}
if ($cek['stok'] < $jumlah) {
    http_response_code(400);
    echo "Stok tidak cukup. Tersedia: " . $cek['stok'];
    exit;
}

// Simpan penjualan
$stmt = $conn->prepare("INSERT INTO penjualan (id_produk, jumlah) VALUES (?, ?)");
$stmt->bind_param("ii", $id_produk, $jumlah);
if ($stmt->execute()) {
    // Kurangi stok
    $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE id_produk = $id_produk");
    echo "Penjualan berhasil disimpan.";
} else {
    http_response_code(500);
    echo "Gagal menyimpan penjualan: " . $stmt->error;
}
