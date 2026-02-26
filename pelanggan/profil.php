<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') { header("Location: ../auth/login.php"); exit; }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo->prepare("UPDATE pelanggan SET nama=?, no_telp=?, alamat=? WHERE id=?")->execute([$_POST['nama'], $_POST['no_telp'], $_POST['alamat'], $_SESSION['user_id']]);
    $msg = "Profil & Alamat berhasil diupdate.";
}
$user = $pdo->prepare("SELECT * FROM pelanggan WHERE id=?"); $user->execute([$_SESSION['user_id']]); $user = $user->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Profil Akun</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-4">
<div class="container bg-white p-4 shadow-sm mx-auto rounded" style="max-width:500px;">
    <h3 class="text-danger mb-4">Profil Akun Saya</h3>
    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
    <form method="POST">
        <label>Email Utama</label><input type="email" class="form-control mb-2 bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly>
        <label>Nama Lengkap</label><input type="text" name="nama" class="form-control mb-2" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required>
        <label>Nomor Telepon/WA</label><input type="text" name="no_telp" class="form-control mb-2" value="<?= htmlspecialchars($user['no_telp'] ?? '') ?>" required>
        <label>Alamat Pengantaran Utama</label><textarea name="alamat" class="form-control mb-4" required><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-danger w-100">Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Tutup</a>
    </form>
</div>
</body>
</html>