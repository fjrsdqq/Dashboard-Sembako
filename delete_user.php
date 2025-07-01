<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) header("Location: login.php");

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT role FROM users WHERE id = $id")->fetch_assoc();
if (!in_array($user['role'], ['admin', 'owner'])) die("Akses ditolak.");

if (isset($_GET['id'])) {
    $hapus_id = (int)$_GET['id'];

    // Jangan hapus diri sendiri
    if ($hapus_id === $id) {
        die("❌ Tidak bisa menghapus akun sendiri.");
    }

    $conn->query("DELETE FROM users WHERE id = $hapus_id");
    header("Location: manajemen_user.php");
    exit();
}
