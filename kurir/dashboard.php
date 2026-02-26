<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'kurir') { header("Location: ../auth/login.php"); exit; }
if (isset($_POST['selesaikan'])) { $pdo->prepare("UPDATE pemesanans SET status='Selesai' WHERE id=?")->execute([$_POST['order_id']]); }

$orders = $pdo->query("SELECT p.*, pel.nama, pel.no_telp, pel.alamat, pkt.nama_paket FROM pemesanans p JOIN pelanggan pel ON p.pelanggan_id = pel.id JOIN detail_pemesanans d ON p.id = d.pemesanan_id JOIN pakets pkt ON d.paket_id = pkt.id WHERE p.status IN ('Menunggu Kurir', 'Selesai') ORDER BY p.tgl_acara ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kurir Panel</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-4">
<h3 class="mb-4 text-danger">Dashboard Kurir - <?= $_SESSION['user_nama'] ?></h3>
<a href="../auth/logout.php" class="btn btn-secondary mb-3">Logout</a>
<div class="row">
    <?php foreach($orders as $o): ?>
    <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm border-<?= $o['status']=='Selesai'?'success':'primary' ?>">
            <h5>Kirim ke: <?= htmlspecialchars($o['nama']) ?></h5>
            <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($o['alamat']) ?></p>
            <p class="mb-1"><strong>Telepon:</strong> <?= htmlspecialchars($o['no_telp']) ?></p>
            <p class="mb-1"><strong>Paket:</strong> <?= htmlspecialchars($o['nama_paket']) ?></p>
            <p class="mb-2"><strong>Status:</strong> <?= ucfirst($o['status']) ?></p>
            <?php if($o['status'] != 'Selesai'): ?>
            <form method="POST"><input type="hidden" name="order_id" value="<?= $o['id'] ?>"><button type="submit" name="selesaikan" class="btn btn-success btn-sm w-100">Tandai Selesai Diantar</button></form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>