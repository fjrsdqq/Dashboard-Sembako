<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil data kategori untuk dropdown
$kategori_result = $conn->query("SELECT * FROM kategori");
?>

<h2>Input Produk Baru</h2>

<form id="form-produk">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama_produk" required><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" min="0" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stok" min="0" required><br><br>

    <label>Kategori:</label><br>
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while ($row = $kategori_result->fetch_assoc()): ?>
            <option value="<?= $row['id_kategori'] ?>"><?= $row['nama_kategori'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Simpan Produk</button>
</form>

<div id="pesan-produk" style="margin-top: 10px;"></div>

<script>
$('#form-produk').on('submit', function(e) {
    e.preventDefault(); // Hindari reload halaman

    $.ajax({
        type: 'POST',
        url: 'simpan_produk.php',
        data: $(this).serialize(),
        success: function(res) {
            $('#pesan-produk').html("<span style='color:green;'>✅ " + res + "</span>");
            $('#form-produk')[0].reset();
        },
        error: function(xhr) {
            $('#pesan-produk').html("<span style='color:red;'>❌ Terjadi kesalahan saat menyimpan produk.</span>");
        }
    });
});
</script>
</table><br><a href='index.php'> ← Kembali ke Dashboard</a>