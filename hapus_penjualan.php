<?php
include "config.php";
$id = $_GET['id'] ?? 0;

// Ambil info penjualan
$cek = $conn->query("SELECT * FROM penjualan WHERE id_penjualan = $id")->fetch_assoc();
if (!$cek) {
    http_response_code(400);
    echo "Data tidak ditemukan.";
    exit;
}

// Hapus dan tambah stok kembali
$conn->query("DELETE FROM penjualan WHERE id_penjualan = $id");
$conn->query("UPDATE produk SET stok = stok + {$cek['jumlah']} WHERE id_produk = {$cek['id_produk']}");
echo "Data berhasil dihapus dan stok dikembalikan.";
