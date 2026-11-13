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
    <title>Dashboard - Aplikasi Kasir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="sidebar">
        <h3>Aplikasi Kasir</h3>
        <p>User: <b><?= $_SESSION['username']; ?></b></p>
        <p>Role: <b><?= ucfirst($role); ?></b></p>
        <hr>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="barang.php"><i class="fas fa-box"></i> Pendataan & Stok Barang</a>
        
        <?php if ($role == 'administrator'): ?>
            <a href="registrasi.php"><i class="fas fa-user-plus"></i> Registrasi Petugas</a>
        <?php endif; ?>
        
        <a href="logout.php" style="color: #ff6b6b; margin-top: 30px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Selamat Datang di Aplikasi Kasir</h2>
            <p>Anda login sebagai <strong><?= strtoupper($role); ?></strong>. Gunakan menu di samping kiri untuk mengelola sistem sesuai hak akses Anda.</p>
        </div>
    </div>

</body>
</html>