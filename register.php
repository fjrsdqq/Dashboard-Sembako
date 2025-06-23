<?php
session_start();
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil role user
$id = $_SESSION['user_id'];
$user = $conn->query("SELECT role FROM users WHERE id = $id")->fetch_assoc();
if (!in_array($user['role'], ['admin', 'owner'])) {
    echo "<p style='color:red;'>⛔ Akses ditolak. Hanya admin/owner yang dapat menambahkan user.</p>";
    exit();
}

// Proses submit
$success = $error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $role     = $_POST['role'];

    if ($password !== $confirm) {
        $error = "❌ Password dan konfirmasi tidak sama!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hash, $role);

        if ($stmt->execute()) {
            $success = "✅ User baru berhasil ditambahkan!";
        } else {
            $error = "❌ Username sudah digunakan atau terjadi kesalahan.";
        }
    }
}
?>

<h2>🆕 Register Pengguna Baru</h2>

<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post" style="max-width:400px;">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Konfirmasi Password:</label><br>
    <input type="password" name="confirm" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="kasir">Kasir</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>
