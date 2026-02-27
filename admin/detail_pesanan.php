<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

if (!isset($_GET['id'])) { header("Location: pesanan.php"); exit; }
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT p.*, pel.nama, pel.no_telp, pel.email, pel.alamat, j.nama_bank, j.no_rekening FROM pemesanans p JOIN pelanggan pel ON p.pelanggan_id = pel.id JOIN jenis_pembayarans j ON p.jenis_pembayaran_id = j.id WHERE p.id = ?"); 
$stmt->execute([$id]);
$order = $stmt->fetch();
if (!$order) { header("Location: pesanan.php"); exit; }

$stmtDetail = $pdo->prepare("SELECT d.*, pkt.nama_paket FROM detail_pemesanans d JOIN pakets pkt ON d.paket_id = pkt.id WHERE d.pemesanan_id = ?");
$stmtDetail->execute([$id]);
$details = $stmtDetail->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Detail Pesanan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <h4>Detail Pesanan #<?= $order['id'] ?></h4>
            <div>
                <?php
                $wa_phone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order['no_telp']));
                $wa_text = urlencode("Halo Kak *" . $order['nama'] . "*,\n\nIni adalah Invoice tagihan pesanan *CateringKu* (Order #" . $order['id'] . ").\nTotal Tagihan: *Rp " . number_format($order['total_harga'],0,',','.') . "*\nTujuan Transfer:\nBank: *" . $order['nama_bank'] . "*\nNo Rek: *" . $order['no_rekening'] . "*\n\nDimohon untuk segera menyelesaikan pembayaran ya. Terima kasih!");
                ?>
                <a href="https://wa.me/<?= $wa_phone ?>?text=<?= $wa_text ?>" target="_blank" class="btn btn-success me-2"><i class="fab fa-whatsapp"></i> Kirim Invoice WA</a>
                <a href="pesanan.php" class="btn btn-secondary">&larr; Kembali</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="border-bottom pb-2">Informasi Pelanggan</h5>
                    <table class="table table-sm table-borderless">
                        <tr><td width="150">Nama</td><td>: <?= htmlspecialchars($order['nama']) ?></td></tr>
                        <tr><td>Email</td><td>: <?= htmlspecialchars($order['email']) ?></td></tr>
                        <tr><td>No. HP / WA</td><td>: <?= htmlspecialchars($order['no_telp']) ?></td></tr>
                        <tr><td>Alamat Kirim</td><td>: <?= htmlspecialchars($order['alamat']) ?></td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm h-100">
                    <h5 class="border-bottom pb-2">Informasi Transaksi</h5>
                    <table class="table table-sm table-borderless">
                        <tr><td width="150">Tgl Dipesan</td><td>: <?= $order['tgl_pesan'] ?></td></tr>
                        <tr><td>Tgl Acara</td><td>: <strong class="text-danger"><?= $order['tgl_acara'] ?></strong></td></tr>
                        <tr><td>Jenis Bayar</td><td>: <?= htmlspecialchars($order['nama_bank']) ?> - <?= htmlspecialchars($order['no_rekening']) ?></td></tr>
                        <tr><td>Status</td><td>: 
                            <span class="badge bg-<?= $order['status']=='Selesai'?'success':($order['status']=='Sedang Diproses'?'primary':'warning text-dark') ?>">
                                <?= $order['status'] ?>
                            </span>
                        </td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <h5 class="border-bottom pb-2">Item yang Dipesan</h5>
            <table class="table table-bordered">
                <thead class="table-light"><tr><th>Paket</th><th>Harga Satuan</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach($details as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nama_paket']) ?></td>
                        <td>Rp <?= number_format($d['harga_satuan'],0,',','.') ?></td>
                        <td><?= $d['jumlah'] ?>x</td>
                        <td>Rp <?= number_format($d['subtotal'],0,',','.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end fw-bold">TOTAL BAYAR</td><td class="fw-bold text-danger">Rp <?= number_format($order['total_harga'],0,',','.') ?></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</body>
</html>