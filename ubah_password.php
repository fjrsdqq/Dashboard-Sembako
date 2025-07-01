<?php
session_start();
include 'config.php'; // pastikan config.php sudah benar terkoneksi ke database minimarket

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi
    if ($password_baru !== $konfirmasi_password) {
        $pesan = "❌ Password baru dan konfirmasi tidak cocok.";
    } else {
        // Cek apakah username terdaftar
        $cek = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $result = $cek->get_result();

        if ($result->num_rows > 0) {
            // Update password
            $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);

            $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update->bind_param("ss", $hashed_password, $username);

            if ($update->execute()) {
                $pesan = "✅ Password berhasil diubah. <a href='login.php'>Login sekarang</a>";
            } else {
                $pesan = "❌ Terjadi kesalahan saat memperbarui password.";
            }
        } else {
            $pesan = "❌ Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .submit-btn {
            width: 100%;
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0d74d1;
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
            color: #1e90ff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>

        <?php if (!empty($pesan)) echo "<div class='message'>$pesan</div>"; ?>

        <form action="" method="POST">
            <label for="username">Username Akun</label>
            <input type="text" id="username" name="username" required>

            <label for="password_baru">Password Baru</label>
            <input type="password" id="password_baru" name="password_baru" required>

            <label for="konfirmasi_password">Konfirmasi Password</label>
            <input type="password" id="konfirmasi_password" name="konfirmasi_password" required>

            <button type="submit" class="submit-btn">Reset Password</button>
        </form>

        <div class="back-link">
            Kembali ke <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
