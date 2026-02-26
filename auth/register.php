<?php
session_start(); require_once '../config/database.php';
if (isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit; }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama']; $email = $_POST['email']; $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telp = $_POST['no_telp']; $alamat = $_POST['alamat'];
    
    $stmt = $pdo->prepare("SELECT id FROM pelanggan WHERE email = ?"); $stmt->execute([$email]);
    if ($stmt->fetch()) { $error = "Email sudah terdaftar!"; } else {
        $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, email, password, no_telp, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $pwd, $telp, $alamat]);
        header("Location: login.php?msg=registered"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Daftar Akun Pelanggan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow-sm" style="width: 400px;">
    <h4 class="text-center mb-4 text-danger">Daftar Akun CateringKu</h4>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Lengkap" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <input type="text" name="no_telp" class="form-control mb-2" placeholder="No WhatsApp" required>
        <textarea name="alamat" class="form-control mb-3" placeholder="Alamat Pengiriman" required></textarea>
        <button type="submit" class="btn btn-danger w-100">Daftar</button>
    </form>
    <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>