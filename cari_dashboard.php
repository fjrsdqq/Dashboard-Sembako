<?php
include "config.php";
if (!isset($_GET['keyword'])) {
    echo "<p>Masukkan kata kunci untuk mencari.</p>";
    exit;
}

$keyword = $conn->real_escape_string($_GET['keyword']);

// Cari produk
$produk = $conn->query("
    SELECT p.nama_produk, p.harga, p.stok, k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
    WHERE p.nama_produk LIKE '%$keyword%' OR k.nama_kategori LIKE '%$keyword%'
");

// Cari kategori
$kategori = $conn->query("
    SELECT * FROM kategori
    WHERE nama_kategori LIKE '%$keyword%'
");

// Cari penjualan
$penjualan = $conn->query("
    SELECT pj.tanggal, p.nama_produk, pj.jumlah, (pj.jumlah * p.harga) AS total
    FROM penjualan pj
    JOIN produk p ON pj.id_produk = p.id_produk
    WHERE p.nama_produk LIKE '%$keyword%' OR pj.tanggal LIKE '%$keyword%'
");

// Hasil Produk
if ($produk->num_rows > 0) {
    echo "<h3>📦 Produk yang cocok:</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
    echo "<tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr>";
    while ($p = $produk->fetch_assoc()) {
        echo "<tr>
                <td>{$p['nama_produk']}</td>
                <td>{$p['nama_kategori']}</td>
                <td>Rp " . number_format($p['harga'], 0, ',', '.') . "</td>
                <td>{$p['stok']}</td>
              </tr>";
    }
    echo "</table><br>";
}

// Hasil Kategori
if ($kategori->num_rows > 0) {
    echo "<h3>📁 Kategori yang cocok:</h3>";
    echo "<ul>";
    while ($k = $kategori->fetch_assoc()) {
        echo "<li>{$k['nama_kategori']}</li>";
    }
    echo "</ul><br>";
}

// Hasil Penjualan
if ($penjualan->num_rows > 0) {
    echo "<h3>🧾 Riwayat Penjualan:</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
    echo "<tr><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Total</th></tr>";
    while ($pj = $penjualan->fetch_assoc()) {
        echo "<tr>
                <td>{$pj['tanggal']}</td>
                <td>{$pj['nama_produk']}</td>
                <td>{$pj['jumlah']}</td>
                <td>Rp " . number_format($pj['total'], 0, ',', '.') . "</td>
              </tr>";
    }
    echo "</table>";
}

if ($produk->num_rows == 0 && $kategori->num_rows == 0 && $penjualan->num_rows == 0) {
    echo "<p>Tidak ditemukan hasil untuk <strong>$keyword</strong>.</p>";
}
?>
