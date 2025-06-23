<?php
session_start();
include "config.php";
if (!isset($_SESSION['user_id'])) header("Location: login.php");
$id = $_SESSION['user_id'];
$role = $conn->query("SELECT role FROM users WHERE id = $id")->fetch_assoc()['role'];
if ($role !== 'admin' && $role !== 'owner') die("Akses ditolak.");

$users = $conn->query("SELECT id, username, role FROM users");
?>

<h2>👥 Daftar Pengguna</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr style="background:#eee;">
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>
    <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <a href="edit_user.php?id=<?= $u['id'] ?>">✏️ Edit</a> |
                <a href="delete_user.php?id=<?= $u['id'] ?>"
                   onclick="return confirm('Yakin ingin menghapus user ini?');">🗑️ Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="register.php">➕ Tambah Pengguna Baru</a>
