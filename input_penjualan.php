<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil data produk untuk dropdown
$produk_result = $conn->query("
    SELECT p.id_produk, p.nama_produk, k.nama_kategori 
    FROM produk p 
    LEFT JOIN kategori k ON p.id_kategori = k.id_kategori
");
?>

<h2>🛒 Input Penjualan</h2>
<form id="form-penjualan">
    <label>Produk:</label>
    <select name="id_produk" required>
        <option value="">--Pilih Produk--</option>
        <?php while ($row = $produk_result->fetch_assoc()): ?>
            <option value="<?= $row['id_produk'] ?>">
                <?= htmlspecialchars($row['nama_produk']) ?>
                <?= $row['nama_kategori'] ? "(".htmlspecialchars($row['nama_kategori']).")" : "" ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" min="1" required><br><br>

    <button type="submit">💾 Simpan</button>
</form>

<div id="pesan-penjualan" style="margin:10px 0;"></div>

<hr>

<h2>📋 Data Penjualan</h2>
<div id="tabel-penjualan">
    <!-- Tabel akan dimuat oleh AJAX -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadTabelPenjualan() {
    $('#tabel-penjualan').load('tabel_penjualan.php');
}

$('#form-penjualan').on('submit', function(e) {
    e.preventDefault();
    $.post('simpan_penjualan.php', $(this).serialize(), function(response) {
        $('#pesan-penjualan').html('<span style="color:green;">✅ ' + response + '</span>');
        $('#form-penjualan')[0].reset();
        loadTabelPenjualan();
    }).fail(function(xhr) {
        $('#pesan-penjualan').html('<span style="color:red;">❌ Gagal: ' + xhr.responseText + '</span>');
    });
});

function hapusPenjualan(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        $.get('hapus_penjualan.php?id=' + id, function(response) {
            $('#pesan-penjualan').html('<span style="color:green;">🗑️ ' + response + '</span>');
            loadTabelPenjualan();
        }).fail(function(xhr) {
            $('#pesan-penjualan').html('<span style="color:red;">❌ Gagal hapus: ' + xhr.responseText + '</span>');
        });
    }
}

$(document).ready(function() {
    loadTabelPenjualan();
});
</script>

<br><a href='index.php'>← Kembali ke Dashboard</a>
