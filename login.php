<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Password salah!";
        }
    } else {
        $error = "❌ Username tidak ditemukan!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Toko Sembako</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: #fff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.7s ease-in-out;
        }
        @keyframes fadeIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .login-box img {
            display: block;
            margin: 0 auto 15px;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #34495e;
        }
        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }
        .login-box button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .login-footer a {
            color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>

<form method="post" class="login-box">
    <!-- Ganti src logo jika kamu punya logo toko -->
    <img src="logo.png" alt="Logo Toko" onerror="this.src='https://via.placeholder.com/100'">
    <h2>Login Toko Sembako</h2>

    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>

    <input type="text" name="username" placeholder="👤 Username" required>
    <input type="password" name="password" placeholder="🔒 Password" required>
    <button type="submit">🔓 Login</button>

    <div class="login-footer">
        Belum punya akun? <a href="daftar.php">Daftar</a><br>
        Lupa password? <a href="ubah_password.php">Reset</a>
    </div>
</form>

</body>
</html>
