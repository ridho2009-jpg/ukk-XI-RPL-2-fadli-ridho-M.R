<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - aplikasi kasir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="sidebar">
        <h3>aplikasi kasir</h3>
        <p>User: <b><?= $_SESSION['username']; ?></b></p>
        <p>Role: <b><?= ucfirst($role); ?></b></p>
        <hr>
        <a href="index.php">dashboard</a>
        <a href="barang.php">pendataan & stok barang</a>
        
        <?php if ($role == 'administrator'): ?>
            <a href="registrasi.php">registrasi petugas</a>
        <?php endif; ?>
        
        <a href="logout.php" style="color: #ff6b6b; margin-top: 30px;">Logout</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Selamat Datang di aplikasi kasir</h2>
            <p>Anda login sebagai <strong><?= strtoupper($role); ?></strong>. Gunakan menu di atas ini untuk mengelola sistem sesuai hak akses Anda</p>
        </div>
    </div>

</body>
</html>