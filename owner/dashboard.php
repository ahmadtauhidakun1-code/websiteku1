<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'owner') { header("Location: ../auth/login.php"); exit; }

$stat = $pdo->query("SELECT COUNT(*) AS trx, SUM(total_harga) AS inc FROM pemesanans WHERE status='Selesai'")->fetch();
$laporan = $pdo->query("SELECT p.*, pel.nama, pkt.nama_paket FROM pemesanans p JOIN pelanggan pel ON p.pelanggan_id = pel.id JOIN detail_pemesanans d ON p.id = d.pemesanan_id JOIN pakets pkt ON d.paket_id = pkt.id WHERE p.status='Selesai' ORDER BY p.tgl_acara DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Owner Panel</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-4">
<h3 class="mb-4 text-danger">Laporan Bisnis - Owner <?= $_SESSION['user_nama'] ?></h3>
<a href="../auth/logout.php" class="btn btn-secondary mb-3">Logout</a>
<button onclick="window.print()" class="btn btn-primary mb-3">Cetak Laporan</button>
<div class="row mb-4">
    <div class="col-md-6"><div class="card p-4 bg-success text-white"><h5>Total Transaksi Sukses</h5><h2><?= $stat['trx'] ?></h2></div></div>
    <div class="col-md-6"><div class="card p-4 bg-danger text-white"><h5>Total Pendapatan</h5><h2>Rp <?= number_format($stat['inc']?:0,0,',','.') ?></h2></div></div>
</div>
<div class="card p-3 shadow-sm">
    <table class="table table-bordered">
        <thead><tr><th>Tgl Acara</th><th>Pelanggan</th><th>Paket</th><th>Pendapatan</th></tr></thead>
        <tbody>
            <?php foreach($laporan as $l): ?>
            <tr><td><?= $l['tgl_acara'] ?></td><td><?= htmlspecialchars($l['nama']) ?></td><td><?= htmlspecialchars($l['nama_paket']) ?></td><td>Rp <?= number_format($l['total_harga'],0,',','.') ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>