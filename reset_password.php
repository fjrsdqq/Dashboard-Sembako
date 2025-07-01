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

// Cek role
$id = $_SESSION['user_id'];
$user = $conn->query("SELECT role FROM users WHERE id = '$id'")->fetch_assoc();
if (!in_array($user['role'], ['admin', 'owner'])) {
    echo "<p style='color:red;'>⛔ Akses ditolak. Hanya admin/owner yang dapat mereset password.</p>";
    exit();
}

$success = $error = "";

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId   = $_POST['user_id'];
    $newPass  = trim($_POST['new_password']);
    $confirm  = trim($_POST['confirm_password']);

    if ($newPass !== $confirm) {
        $error = "❌ Password dan konfirmasi tidak sama!";
    } elseif (strlen($newPass) < 5) {
        $error = "❌ Password terlalu pendek, minimal 5 karakter!";
    } else {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $userId); // benar: 'si'
        if ($stmt->execute()) {
            $success = "✅ Password berhasil direset!";
        } else {
            $error = "❌ Gagal mereset password! " . $stmt->error;
        }
        $stmt->close();
    }
}

// Ambil daftar user
$list = $conn->query("SELECT id, username, role FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 20px;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #2980b9;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #3498db;
        }
        .msg-success {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .msg-error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<form id="resetForm" method="post" action="reset_password.php">
    <h2>🔐 Reset Password Pengguna</h2>

    <?php if ($success): ?>
        <div class="msg-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="msg-error"><?= $error ?></div>
    <?php endif; ?>

    <label>Pilih User:</label>
    <select name="user_id" required>
        <option value="">-- Pilih --</option>
        <?php while ($u = $list->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?> (<?= $u['role'] ?>)</option>
        <?php endwhile; ?>
    </select>

    <label>Password Baru:</label>
    <input type="password" name="new_password" required>

    <label>Konfirmasi Password:</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">🔁 Reset Password</button>
</form>

<!-- Tambahan AJAX agar tidak reload -->
<script>
document.querySelector("#resetForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch("reset_password.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        document.querySelector("#content-area").innerHTML = html;
    })
    .catch(err => alert("Terjadi kesalahan: " + err));
});
</script>

</body>
</html>
