<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<h2>Input Kategori Baru</h2>

<form id="form-kategori">
    <label>Nama Kategori:</label><br>
    <input type="text" name="nama_kategori" required><br><br>
    <button type="submit">Simpan Kategori</button>
</form>

<div id="pesan-kategori" style="margin-top: 10px;"></div>

<script>
$('#form-kategori').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'simpan_kategori.php',
        data: $(this).serialize(),
        success: function(res) {
            $('#pesan-kategori').html('<span style="color:green;">✅ ' + res + '</span>');
            $('#form-kategori')[0].reset();
        },
        error: function(xhr) {
            $('#pesan-kategori').html('<span style="color:red;">❌ ' + xhr.responseText + '</span>');
        }
    });
});
</script>
</table><br><a href='index.php'> ← Kembali ke Dashboard</a>