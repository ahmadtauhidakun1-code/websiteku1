<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') { header("Location: ../auth/login.php"); exit; }

$orders = $pdo->prepare("SELECT p.*, d.paket_id, pkt.nama_paket FROM pemesanans p JOIN detail_pemesanans d ON p.id = d.pemesanan_id JOIN pakets pkt ON d.paket_id = pkt.id WHERE p.pelanggan_id=? ORDER BY p.tgl_pesan DESC");
$orders->execute([$_SESSION['user_id']]); $riwayat = $orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Riwayat Pesanan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-5">
<div class="container bg-white p-4 border rounded shadow-sm">
    <div class="d-flex justify-content-between mb-4">
        <h3 class="text-danger">Riwayat Pemesanan Saya</h3>
        <a href="dashboard.php" class="btn btn-outline-danger">Kembali ke Katalog</a>
    </div>
    <div class="row">
        <?php foreach($riwayat as $r): ?>
        <div class="col-12 mb-3">
            <div class="card p-3 shadow-sm border-start border-4 <?= $r['status']=='Selesai'?'border-success':'border-danger' ?>">
                <div class="d-flex justify-content-between">
                    <h5><?= htmlspecialchars($r['nama_paket']) ?></h5>
                    <span class="badge <?= $r['status']=='Selesai'?'bg-success':'bg-warning text-dark' ?> fs-6"><?= ucfirst($r['status']) ?></span>
                </div>
                <p class="mb-1 text-muted">Tanggal Acara: <?= $r['tgl_acara'] ?></p>
                <p class="mb-0 fw-bold">Total Harga: Rp <?= number_format($r['total_harga'],0,',','.') ?></p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(count($riwayat)==0) echo "<p class='text-center text-muted'>Belum ada riwayat pesanan.</p>"; ?>
    </div>
</div>
</body>
</html>