<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';

$db = new Koneksi();
$role = $_SESSION['role'];

if (isset($_GET['hapus'])) {
    if ($role == 'administrator') {
        $id = $_GET['hapus'];
        $stmt = $db->koneksi->prepare("DELETE FROM barang WHERE idbarang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: barang.php");
        exit;
    }
}

$data_barang = $db->koneksi->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendataan & Pembelian Barang</title>
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
        
        <?php if ($role == 'administrator' || $role == 'petugas'): ?>
            <a href="detail_penjualan.php"><i class="fas fa-cash-register"></i> Transaksi Penjualan</a>
        <?php endif; ?>
        
        <?php if ($role == 'administrator'): ?>
            <a href="registrasi.php"><i class="fas fa-user-plus"></i> Registrasi Petugas</a>
        <?php endif; ?>
        
        <a href="logout.php" style="color: #ff6b6b; margin-top: 30px;"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="card">
            <h2>Daftar Barang Toko</h2>
            <p>
                <?php if ($role == 'pelanggan'): ?>
                    Silakan pilih barang yang ingin Anda beli.
                <?php else: ?>
                    Berikut adalah daftar barang yang tersedia di kasir:
                <?php endif; ?>
            </p>
            
            <?php if ($role == 'administrator'): ?>
                <a href="tambah_barang.php" class="btn" style="background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px;"><i class="fas fa-plus"></i> Tambah Barang Baru</a>
            <?php endif; ?>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php while($row = $data_barang->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['idbarang']; ?></td>
                    <td><?= $row['namabarang']; ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td>
                        <?php if ($role == 'pelanggan'): ?>
                            <?php if ($row['stok'] > 0): ?>
                                <a href="detail_penjualan.php?id=<?= $row['idbarang']; ?>" style="background: #28a745; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 13px;"><i class="fas fa-shopping-cart"></i> Beli</a>
                            <?php else: ?>
                                <span style="color: red; font-size: 12px; font-weight: bold;">Stok Habis</span>
                            <?php endif; ?>

                        <?php elseif ($role == 'administrator'): ?>
                            <a href="edit_barang.php?id=<?= $row['idbarang']; ?>" style="background: #ffc107; color: #333; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 13px;"><i class="fas fa-edit"></i> Edit</a>
                            <a href="barang.php?hapus=<?= $row['idbarang']; ?>" style="background: #dc3545; color: white; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 13px;" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i> Hapus</a>

                        <?php else: ?>
                            <a href="edit_barang.php?id=<?= $row['idbarang']; ?>" style="background: #ffc107; color: #333; padding: 4px 8px; text-decoration: none; border-radius: 3px; font-size: 13px;"><i class="fas fa-edit"></i> Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</body>
</html>