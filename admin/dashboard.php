<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

$stmtP = $pdo->query("SELECT COUNT(*) FROM pakets"); $totalPack = $stmtP->fetchColumn();
$stmtPend = $pdo->query("SELECT COUNT(*) FROM pemesanans WHERE status='Menunggu Konfirmasi'"); $totalPend = $stmtPend->fetchColumn();
$stmtSel = $pdo->query("SELECT COUNT(*) FROM pemesanans WHERE status='Selesai'"); $totalSel = $stmtSel->fetchColumn();
$stmtInc = $pdo->query("SELECT SUM(total_harga) FROM pemesanans WHERE status='Selesai'"); $totalInc = $stmtInc->fetchColumn() ?: 0;
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Admin Dashboard</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
            <h4>Selamat Datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</h4>
            <div class="row mt-4">
                <div class="col-md-3"><div class="card bg-primary text-white p-3 shadow-sm"><h5>Total Paket</h5><h2><?= $totalPack ?></h2></div></div>
                <div class="col-md-3"><div class="card bg-warning text-dark p-3 shadow-sm"><h5>Menunggu Konfirmasi</h5><h2><?= $totalPend ?></h2></div></div>
                <div class="col-md-3"><div class="card bg-success text-white p-3 shadow-sm"><h5>Pesanan Selesai</h5><h2><?= $totalSel ?></h2></div></div>
                <div class="col-md-3"><div class="card bg-danger text-white p-3 shadow-sm"><h5>Total Pendapatan</h5><h2>Rp <?= number_format($totalInc,0,',','.') ?></h2></div></div>
            </div>
        </div>
    </div>
</body>
</html>