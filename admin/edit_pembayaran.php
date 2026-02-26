<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

if (!isset($_GET['id'])) { header("Location: jenis_pembayaran.php"); exit; }
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE jenis_pembayarans SET nama_bank=?, no_rekening=?, atas_nama=? WHERE id=?");
    $stmt->execute([$_POST['nama_bank'], $_POST['no_rekening'], $_POST['atas_nama'], $id]);
    header("Location: jenis_pembayaran.php"); exit;
}

$stmt = $pdo->prepare("SELECT * FROM jenis_pembayarans WHERE id=?"); $stmt->execute([$id]);
$bank = $stmt->fetch();
if (!$bank) { header("Location: jenis_pembayaran.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Pembayaran</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-light">
<div class="d-flex">
    <div class="bg-white p-3 vh-100 shadow-sm" style="width: 250px;">
        <h4 class="text-danger fw-bold"><i class="fas fa-user-shield"></i> AdminPanel</h4><hr>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active text-danger fw-bold':'text-dark' ?>" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'paket')!==false?'active text-danger fw-bold':'text-dark' ?>" href="paket.php">Kelola Paket</a></li>
            <li class="nav-item"><a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'pembayaran')!==false?'active text-danger fw-bold':'text-dark' ?>" href="jenis_pembayaran.php">Metode Pembayaran</a></li>
            <li class="nav-item"><a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'pesanan')!==false?'active text-danger fw-bold':'text-dark' ?>" href="pesanan.php">Kelola Pesanan</a></li>
            <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='pengguna.php'?'active text-danger fw-bold':'text-dark' ?>" href="pengguna.php">Kelola Pengguna</a></li>
            <li class="nav-item mt-4"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="flex-grow-1 p-4">
        <div class="bg-white p-4 rounded shadow-sm" style="max-width:500px;">
            <h4 class="mb-4">Edit Metode Pembayaran</h4>
            <form method="POST">
                <div class="mb-3"><label>Nama Bank / E-Wallet</label><input type="text" name="nama_bank" class="form-control" value="<?= htmlspecialchars($bank['nama_bank']) ?>" required></div>
                <div class="mb-3"><label>Nomor Rekening</label><input type="text" name="no_rekening" class="form-control" value="<?= htmlspecialchars($bank['no_rekening']) ?>" required></div>
                <div class="mb-3"><label>Atas Nama</label><input type="text" name="atas_nama" class="form-control" value="<?= htmlspecialchars($bank['atas_nama']) ?>" required></div>
                <button type="submit" class="btn btn-danger">Update</button>
                <a href="jenis_pembayaran.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>