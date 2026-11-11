<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';

$db = new Koneksi();

if (isset($_GET['hapus'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator') {
        $id = $_GET['hapus'];
        $stmt = $db->koneksi->prepare("DELETE FROM barang WHERE idbarang = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: barang.php");
            exit;
        } else {
            echo "Gagal menghapus data.";
        }
    } else {
        echo "<script>alert('Akses ditolak! Hanya Administrator yang dapat menghapus barang.'); window.location='barang.php';</script>";
        exit;
    }
}

$data_barang = $db->koneksi->query("SELECT * FROM barang");
?>

<!DOCTYPE html>
<html lang="id">
<head>
</head>
<body>

    <div class="container">
        <div class="sidebar">
            <h3>aplikasi kasir</h3>
            <p>User: <br><b><?= $_SESSION['username']; ?></b><br>
               <small style="color: #666;">Role: <?= ucfirst($_SESSION['role']); ?></small>
            </p>
            <hr>
            <a href="index.php"><i class="fas fa-home"></i> dashboard</a>
            <a href="barang.php"><i class="fas fa-box"></i> pendataan barang</a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator'): ?>
                <a href="tambah_petugas.php"><i class="fas fa-user-plus"></i> tambah petugas</a>
            <?php endif; ?>

            <a href="logout.php" style="color: #d73a49; margin-top: 20px;"><i class="fas fa-sign-out-alt"></i> logout</a>
        </div>

        <div class="content">
            <div class="card">
                <h2>data stok barang</h2>
                <p>daftar barang yang tersedia di kasir:</p>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator'): ?>
                    <a href="tambah_barang.php" class="btn" style="background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px; font-size: 14px;"><i class="fas fa-plus"></i> tambah barang baru</a>
                <?php endif; ?>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                    <?php if ($data_barang->num_rows > 0): ?>
                        <?php while($row = $data_barang->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['idbarang']; ?></td>
                            <td><?= $row['namabarang']; ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['stok']; ?></td>
                            
                            <td>
                                <a href="edit_barang.php?id=<?= $row['idbarang']; ?>" style="background: #ffc107; color: #333; padding: 5px 8px; text-decoration: none; border-radius: 3px; font-size: 13px;"><i class="fas fa-edit"></i> Edit</a>
                                
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'administrator'): ?>
                                    <a href="barang.php?hapus=<?= $row['idbarang']; ?>" style="background: #dc3545; color: white; padding: 5px 8px; text-decoration: none; border-radius: 3px; font-size: 13px; margin-left: 4px;" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')"><i class="fas fa-trash"></i> Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #586069;">Belum ada data barang.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

</body>
</html>