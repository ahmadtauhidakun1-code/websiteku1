<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
if (isset($_GET['hapus'])) { $pdo->prepare("DELETE FROM pakets WHERE id = ?")->execute([$_GET['hapus']]); header("Location: paket.php"); exit; }
$packages = $pdo->query("SELECT * FROM pakets")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kelola Paket</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
        <div class="d-flex justify-content-between mb-3"><h4>Daftar Paket Catering</h4><a href="paket_tambah.php" class="btn btn-danger">Tambah Paket</a></div>
        <table class="table table-bordered bg-white shadow-sm">
            <thead><tr><th>No</th><th>Nama Paket</th><th>Harga</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $no=1; foreach($packages as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($p['nama_paket']) ?></td>
                    <td>Rp <?= number_format($p['harga'],0,',','.') ?></td>
                    <td><?= substr(htmlspecialchars($p['deskripsi']), 0, 50) ?>...</td>
                    <td>
                        <a href="edit_paket.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus paket ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(!$packages) echo "<tr><td colspan='5' class='text-center'>Data tidak ditemukan</td></tr>"; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>