<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';
$db = new Koneksi();

if (!isset($_GET['id'])) {
    header("Location: barang.php");
    exit;
}

$idbarang = $_GET['id'];
$query = $db->koneksi->query("SELECT * FROM barang WHERE idbarang = '$idbarang'");
$barang = $query->fetch_assoc();

if (isset($_POST['beli'])) {
    $jumlah = $_POST['jumlah'];

    if ($jumlah > 0 && $jumlah <= $barang['stok']) {
        $tanggal = date('Y-m-d H:i:s');
        $totalharga = $barang['harga'] * $jumlah;
        
        // Gunakan ID pelanggan default (misal ID 1, atau pastikan data pelanggan 'Umum' ada di database)
        $idpelanggan = 1; 

        // 1. Simpan ke tabel penjualan dengan idpelanggan
        $db->koneksi->query("INSERT INTO penjualan (tanggalpenjualan, totalharga, idpelanggan) VALUES ('$tanggal', '$totalharga', '$idpelanggan')");
        $idpenjualan = $db->koneksi->insert_id;

        // 2. Simpan ke detail penjualan
        $db->koneksi->query("INSERT INTO detail_penjualan (idpenjualan, idbarang, jumlahproduk, subtotal) VALUES ('$idpenjualan', '$idbarang', '$jumlah', '$totalharga')");

        // 3. Kurangi stok barang
        $stok_sisa = $barang['stok'] - $jumlah;
        $db->koneksi->query("UPDATE barang SET stok = '$stok_sisa' WHERE idbarang = '$idbarang'");

        echo "<script>alert('Pembelian berhasil!'); window.location='barang.php';</script>";
        exit;
    } else {
        $pesan = "Stok tidak mencukupi atau jumlah tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card" style="max-width: 400px; margin: 20px auto;">
        <h3>Form Pembelian Barang</h3>
        <?php if (isset($pesan)) echo "<p style='color: red;'>$pesan</p>"; ?>

        <form method="POST">
            <p>Nama Barang: <b><?= htmlspecialchars($barang['namabarang']); ?></b></p>
            <p>Harga: Rp <?= number_format($barang['harga'], 0, ',', '.'); ?></p>
            <p>Stok: <?= $barang['stok']; ?></p>

            Jumlah Beli:<br>
            <input type="number" name="jumlah" value="1" min="1" max="<?= $barang['stok']; ?>" required style="width: 100%; padding: 5px; margin-bottom: 10px;"><br>

            <button type="submit" name="beli">Proses Beli</button>
            <a href="barang.php">Batal</a>
        </form>
    </div>
</body>
</html>