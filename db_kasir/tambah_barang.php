<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';

$db = new Koneksi();
$pesan = "";

if (isset($_POST['tambah'])) {
    $namabarang = $_POST['namabarang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (!empty($namabarang) && !empty($harga) && !empty($stok)) {
        $stmt = $db->koneksi->prepare("INSERT INTO barang (namabarang, harga, stok) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $namabarang, $harga, $stok);
        
        if ($stmt->execute()) {
            header("Location: barang.php");
            exit;
        } else {
            $pesan = "Gagal menambah barang ke database.";
        }
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
</head>
<body>

    <div class="container">
        <div class="sidebar">
            <h3>aplikasi kasir</h3>
            <p>User: <br><b><?= $_SESSION['username']; ?></b></p>
            <hr>
            <a href="index.php"><i class="fas fa-home"></i> dashboard</a>
            <a href="barang.php"><i class="fas fa-box"></i> pendataan barang</a>
            <a href="logout.php" style="color: #d73a49; margin-top: 20px;"><i class="fas fa-sign-out-alt"></i> keluar</a>
        </div>

        <div class="content">
            <div class="card" style="max-width: 500px;">
                <h2>tambah barang baru</h2>
                <p>Silakan masukkan barang yang ingin ditambahkan.</p>

                <?php if ($pesan): ?>
                    <div style="background: #ffe3e3; color: #c92a2a; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px;">
                        <?= $pesan; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">nama barang</label>
                        <input type="text" name="namabarang" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">harga (Rp)</label>
                        <input type="number" name="harga" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">stok</label>
                        <input type="number" name="stok" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <button type="submit" name="tambah" class="btn" style="background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-save"></i> simpan barang
                    </button>
                    <a href="barang.php" style="background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 5px; font-size: 14px;">batal</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>