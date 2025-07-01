<?php
session_start();
include 'config.php';

$pesan = '';

// Pastikan ada ID
if (!isset($_GET['id'])) {
    die("❌ ID tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data user berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("❌ Data user tidak ditemukan.");
}

$user = $result->fetch_assoc();

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password_baru = $_POST['password_baru'];

    if (!empty($password_baru)) {
        // Ubah password juga
        $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
        $update = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        $update->bind_param("sssi", $username, $hashed_password, $role, $id);
    } else {
        // Tidak ubah password
        $update = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $update->bind_param("ssi", $username, $role, $id);
    }

    if ($update->execute()) {
        $pesan = "✅ Data user berhasil diperbarui.";
        // Ambil data terbaru setelah update
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $pesan = "❌ Gagal memperbarui data user.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .submit-btn {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background-color: #fef3c7;
            color: #92400e;
        }

        .back-link {
            text-align: center;
            margin-top: 10px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Data User</h2>

        <?php if (!empty($pesan)) echo "<div class='message'>$pesan</div>"; ?>

        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="kasir" <?= $user['role'] === 'kasir' ? 'selected' : '' ?>>Kasir</option>
            </select>

            <label for="password_baru">Password Baru (opsional)</label>
            <input type="password" name="password_baru" id="password_baru" placeholder="Kosongkan jika tidak ingin mengubah password">

            <button type="submit" class="submit-btn">Simpan Perubahan</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Kembali ke Home</a>
        </div>
    </div>
</body>
</html>
