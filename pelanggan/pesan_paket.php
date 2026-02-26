<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'pelanggan') { header("Location: ../auth/login.php"); exit; }
$stmt = $pdo->prepare("SELECT * FROM pakets WHERE id=?"); $stmt->execute([$_GET['id'] ?? null]); $package = $stmt->fetch();
if(!$package){ header("Location: dashboard.php"); exit; }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Insert ke pemesanans
    $stmt = $pdo->prepare("INSERT INTO pemesanans (pelanggan_id, tgl_acara, total_harga, status, jenis_pembayaran_id) VALUES (?, ?, ?, 'Menunggu Konfirmasi', 1)");
    $stmt->execute([$_SESSION['user_id'], $_POST['tanggal_kirim'], $package['harga']]);
    $pemesanan_id = $pdo->lastInsertId();
    
    // 2. Insert ke detail_pemesanans
    $stmtDetail = $pdo->prepare("INSERT INTO detail_pemesanans (pemesanan_id, paket_id, jumlah, harga_satuan, subtotal) VALUES (?, ?, 1, ?, ?)");
    $stmtDetail->execute([$pemesanan_id, $package['id'], $package['harga'], $package['harga']]);
    
    // Catatan: Karena DB tidak ada field catatan di tabel pesanan asli, bisa biarkan dulu.
    
    header("Location: riwayat.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Form Pemesanan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light p-5">
<div class="container bg-white p-4 shadow-sm rounded" style="max-width:600px;">
    <h3 class="text-danger border-bottom pb-2">Buat Pemesanan</h3>
    <h5 class="mt-3"><?= htmlspecialchars($package['nama_paket']) ?> - Rp <?= number_format($package['harga'],0,',','.') ?></h5>
    <form method="POST" class="mt-4">
        <label>Tanggal Acara / Pengiriman</label><input type="date" name="tanggal_kirim" class="form-control mb-3" required min="<?= date('Y-m-d') ?>">
        <label>Catatan Tambahan Kepada Koki / Kurir</label><textarea name="catatan" class="form-control mb-3" placeholder="Contoh: Kurangi Pedas.."></textarea>
        <button type="submit" class="btn btn-danger w-100">Checkout / Pesan Sekarang</button>
        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>
</body>
</html>