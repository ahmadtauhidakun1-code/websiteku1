<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
if (isset($_GET['hapus'])) { $pdo->prepare("DELETE FROM jenis_pembayarans WHERE id = ?")->execute([$_GET['hapus']]); header("Location: jenis_pembayaran.php"); exit; }
$banks = $pdo->query("SELECT * FROM jenis_pembayarans")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kelola Pembayaran</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
        <div class="d-flex justify-content-between mb-3"><h4>Daftar Metode Pembayaran</h4><a href="tambah_pembayaran.php" class="btn btn-danger">Tambah Bank / E-Wallet</a></div>
        <table class="table table-bordered bg-white shadow-sm">
            <thead><tr><th>No</th><th>Nama Bank / Metode</th><th>No Rekening</th><th>Atas Nama</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $no=1; foreach($banks as $b): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($b['nama_bank']) ?></td>
                    <td><?= htmlspecialchars($b['no_rekening']) ?></td>
                    <td><?= htmlspecialchars($b['atas_nama']) ?></td>
                    <td>
                        <a href="edit_pembayaran.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="?hapus=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus metode ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(!$banks) echo "<tr><td colspan='5' class='text-center'>Data Bank / Pembayaran masih kosong. Tambahkan dulu.</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>