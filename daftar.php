<?php
session_start();
include 'config.php'; // pastikan file ini berisi koneksi ke database "minimarket"

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'kasir'; // default role bisa diubah sesuai kebutuhan

    // Cek apakah username sudah ada
    $cek = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $pesan = "❌ Username sudah digunakan, silakan pilih yang lain.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $pesan = "✅ Pendaftaran berhasil. <a href='login.php'>Login di sini</a>";
        } else {
            $pesan = "❌ Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
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
        input[type="email"],
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

        .login-link {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .login-link a {
            color: #1e90ff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background-color: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Daftar Akun Baru</h2>

        <?php if (!empty($pesan)) echo "<div class='message'>$pesan</div>"; ?>

        <form action="" method="POST">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="submit-btn">Daftar</button>
        </form>
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>
