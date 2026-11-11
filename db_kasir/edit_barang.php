<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';

$db = new Koneksi();
$pesan = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: barang.php");
    exit;
}

$id = $_GET['id'];

$stmt_cek = $db->koneksi->prepare("SELECT * FROM barang WHERE idbarang = ?");
$stmt_cek->bind_param("i", $id);
$stmt_cek->execute();
$result = $stmt_cek->get_result();

if ($result->num_rows == 0) {
    header("Location: barang.php");
    exit;
}

$row = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $namabarang = $_POST['namabarang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    if (!empty($namabarang) && !empty($harga) && !empty($stok)) {
        $stmt_update = $db->koneksi->prepare("UPDATE barang SET namabarang = ?, harga = ?, stok = ? WHERE idbarang = ?");
        $stmt_update->bind_param("sdii", $namabarang, $harga, $stok, $id);

        if ($stmt_update->execute()) {
            header("Location: barang.php");
            exit;
        } else {
            $pesan = "Gagal memperbarui data barang.";
        }
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>edit Barang</title>
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
            <a href="logout.php" style="color: #d73a49; margin-top: 20px;"><i class="fas fa-sign-out-alt"></i> logout</a>
        </div>

        <div class="content">
            <div class="card" style="max-width: 500px;">
                <h2>Edit Data Barang</h2>
                <p>ubah data barang ini </p>

                <?php if ($pesan): ?>
                    <div style="background: #ffe3e3; color: #c92a2a; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px;">
                        <?= $pesan; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Barang</label>
                        <input type="text" name="namabarang" value="<?= htmlspecialchars($row['namabarang']); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Harga (Rp)</label>
                        <input type="number" name="harga" value="<?= $row['harga']; ?>" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Stok</label>
                        <input type="number" name="stok" value="<?= $row['stok']; ?>" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <button type="submit" name="update" class="btn" style="background: #ffc107; color: #333; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        <i class="fas fa-save"></i> update data 
                    </button>
                    <a href="barang.php" style="background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 5px; font-size: 14px;">Batal</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>