<?php
session_start();
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];
$result = $conn->query("SELECT username, role FROM users WHERE id = $id");
$user = $result->fetch_assoc();
$username = $user['username'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Minimarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 230px;
            height: 100%;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
            border-radius: 4px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
        }

        h1 { color: #2c3e50; }

        #searchMenu, #searchDashboard {
            padding: 10px;
            width: 90%;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 10px auto 20px;
            display: block;
        }

        #content-area {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .card-box {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            min-width: 180px;
            padding: 20px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .produk { background-color: #2980b9; }
        .kategori { background-color: #27ae60; }
        .penjualan { background-color: #e67e22; }

        .card h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }
        .card p {
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 10px;
            }

            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>📊 Management Toko Sembako</h2>
    <p style="text-align:center; font-size:14px;">👋 <?= htmlspecialchars($username) ?> (<?= $role ?>)</p>
    <input type="text" id="searchMenu" placeholder="Cari menu...">
    <ul id="menuList">
        <li><a href="index.php">🏠 Home</a></li>
        <li><a href="input_produk.php" class="menu-link">➕ Input Produk</a></li>
        <li><a href="input_penjualan.php" class="menu-link">🛒 Input Penjualan</a></li>
        <li><a href="list_produk.php" class="menu-link">📦 Lihat Data Produk</a></li>
        <li><a href="list_penjualan.php" class="menu-link">🧾 Lihat Data Penjualan</a></li>
        <li><a href="input_kategori.php" class="menu-link">📁 Input Kategori</a></li>
        <li><a href="list_kategori.php" class="menu-link">📂 Lihat Data Kategori</a></li>
        <?php if ($role === 'admin' || $role === 'owner'): ?>
            <li><a href="manajemen_user.php" class="menu-link">👤 Manajemen User</a></li>
        <?php endif; ?>
<<<<<<< HEAD
=======
        <li><a href="register.php" class="menu-link">🆕 Register Pengguna</a></li>
>>>>>>> a1bbefe95640d65c563394f6c3b18317460b260b
        <li><a href="reset_password.php" class="menu-link">🔐 Reset Password</a></li>
        <li><a href="ganti_password.php" class="menu-link">🔁 Ganti Password</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1>Dashboard Minimarket</h1>
    <input type="text" id="searchDashboard" placeholder="🔍 Cari produk, kategori, atau penjualan...">
    <div id="content-area">
        <?php include "dashboard_home.php"; ?>
    </div>
</div>

<script>
    $('#searchMenu').on('keyup', function () {
        const keyword = $(this).val().toLowerCase();
        $('#menuList li').each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(keyword));
        });
    });

    $('.menu-link').on('click', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');
        $('#content-area').html('<p>Loading...</p>');
        $.get(href, function (data) {
            $('#content-area').html(data);
        });
    });

    $('#searchDashboard').on('keyup', function() {
        const keyword = $(this).val();
        if (keyword.length > 1) {
            $.get('cari_dashboard.php', { keyword: keyword }, function(data) {
                $('#content-area').html(data);
            });
        } else {
            $.get('dashboard_home.php', function(data) {
                $('#content-area').html(data);
            });
        }
    });
</script>
</body>
</html>
