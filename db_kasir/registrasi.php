<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'administrator') {
    header("Location: login.php");
    exit;
}
require_once 'Koneksi.php';
$db = new Koneksi();

$pesan = "";
if (isset($_POST['tambah_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt_cek = $db->koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt_cek->bind_param("s", $username);
    $stmt_cek->execute();
    $cek = $stmt_cek->get_result();

    if ($cek->num_rows > 0) {
        $pesan = "Username sudah digunakan!";
    } else {
        $stmt = $db->koneksi->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        if ($stmt->execute()) {
            header("Location: index.php"); 
            exit;
        } else {
            $pesan = "gagal mendaftarkan akun.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Akun Petugas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .card { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 400px; margin: auto; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 4px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #28a745; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px; width: 100%; font-weight: bold; }
        .error { color: red; margin-bottom: 10px; font-size: 13px; }
        .back-link { display: block; text-align: center; margin-top: 10px; text-decoration: none; color: #6c757d; }
    </style>
</head>
<body>
    <div class="card">
        <h3>registrasi akun baru</h3>
        <?php if($pesan): ?>
            <div class="error"><?= $pesan; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role / Hak Akses</label>
                <select name="role">
                    <option value="petugas">Petugas</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>
            <button type="submit" name="tambah_user" class="btn">daftarkan akun</button>
            <a href="index.php" class="back-link">kembali ke dashboard</a>
        </form>
    </div>
</body>
</html>