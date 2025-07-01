<?php
include "config.php";
$id = $_GET['id'] ?? 0;

$result = $conn->query("SELECT * FROM penjualan WHERE id_penjualan = $id");
$data = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("UPDATE penjualan SET id_produk=?, jumlah=? WHERE id_penjualan=?");
    $stmt->bind_param("iii", $id_produk, $jumlah, $id);
    if ($stmt->execute()) {
        header("Location: input_penjualan.php");
        exit;
    } else {
        echo "Gagal update: " . $stmt->error;
    }
}
?>

<h2>Edit Penjualan</h2>
<form method="POST" action="">
    <label>Produk:</label>
    <select name="id_produk" required>
        <?php
        $produk = $conn->query("SELECT * FROM produk");
        while ($row = $produk->fetch_assoc()) {
            $selected = ($row['id_produk'] == $data['id_produk']) ? "selected" : "";
            echo "<option value='{$row['id_produk']}' $selected>{$row['nama_produk']}</option>";
        }
        ?>
    </select><br><br>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" min="1" value="<?= $data['jumlah'] ?>" required><br><br>

    <button type="submit" name="update">Update</button>
</form>
