<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') { header("Location: ../auth/login.php"); exit; }
$packages = $pdo->query("SELECT * FROM pakets")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Katalog CateringKu</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm p-3 mb-4">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="#">CateringKu</a>
        <div class="ms-auto">
            <a href="dashboard.php" class="btn btn-outline-danger me-2">Katalog</a>
            <a href="riwayat.php" class="btn btn-outline-danger me-2">Riwayat</a>
            <a href="profil.php" class="btn btn-outline-danger me-2">Profil</a>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>
<div class="container">
    <h3 class="mb-4">Katalog Paket Catering Terbaik</h3>
    <div class="row">
        <?php foreach($packages as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 p-3 text-center">
                <h5 class="fw-bold text-danger"><?= htmlspecialchars($p['nama_paket']) ?></h5>
                <h4 class="text-dark fw-bold mb-2">Rp <?= number_format($p['harga'],0,',','.') ?></h4>
                <p class="text-muted small"><?= htmlspecialchars($p['deskripsi']) ?></p>
                <a href="pesan_paket.php?id=<?= $p['id'] ?>" class="btn btn-danger mt-auto">Pesan Sekarang</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>