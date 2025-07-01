<?php
include "config.php";

// Data akun admin yang ingin dibuat
$username = 'adminmaster';
$password = 'admin123';
$role = 'admin';

// Hash password-nya
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Cek apakah username sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "❌ Username '$username' sudah terdaftar.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "✅ Admin berhasil dibuat! Username: $username, Password: $password";
    } else {
        echo "❌ Gagal membuat admin: " . $stmt->error;
    }
}
?>
