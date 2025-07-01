<?php
session_start();
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ambil password lama dari database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Validasi
    if (!password_verify($current_password, $hashed_password)) {
        $error = "❌ Password lama salah!";
    } elseif ($new_password !== $confirm_password) {
        $error = "❌ Konfirmasi password tidak cocok!";
    } else {
        $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->bind_param("si", $new_hashed, $user_id);
        if ($update->execute()) {
            $success = "✅ Password berhasil diubah.";
        } else {
            $error = "❌ Gagal mengubah password.";
        }
        $update->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>🔁 Ganti Password</h2>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="ganti_password.php">
        <label>Password Lama:</label>
        <input type="password" name="current_password" required>

        <label>Password Baru:</label>
        <input type="password" name="new_password" required>

        <label>Konfirmasi Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
