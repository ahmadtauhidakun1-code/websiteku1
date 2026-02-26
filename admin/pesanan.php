<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }
if (isset($_POST['update_status'])) { $pdo->prepare("UPDATE pemesanans SET status=? WHERE id=?")->execute([$_POST['status'], $_POST['order_id']]); }

$orders = $pdo->query("SELECT p.*, pel.nama, pkt.nama_paket FROM pemesanans p JOIN pelanggan pel ON p.pelanggan_id = pel.id JOIN detail_pemesanans d ON p.id = d.pemesanan_id JOIN pakets pkt ON d.paket_id = pkt.id ORDER BY p.tgl_pesan DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kelola Pesanan</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
        <h4>Kelola Pesanan Masuk</h4>
        <div class="table-responsive">
            <table class="table table-bordered bg-white mt-3 shadow-sm align-middle">
                <thead><tr><th>Tgl Order</th><th>Tgl Acara</th><th>Pelanggan</th><th>Paket</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach($orders as $o): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($o['tgl_pesan'])) ?></td>
                        <td class="fw-bold text-danger"><?= date('d/m/Y', strtotime($o['tgl_acara'])) ?></td>
                        <td><?= htmlspecialchars($o['nama']) ?></td>
                        <td><?= htmlspecialchars($o['nama_paket']) ?></td>
                        <td>Rp <?= number_format($o['total_harga'],0,',','.') ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="Menunggu Konfirmasi" <?= $o['status']=='Menunggu Konfirmasi'?'selected':'' ?>>Konfirmasi</option>
                                    <option value="Sedang Diproses" <?= $o['status']=='Sedang Diproses'?'selected':'' ?>>Diproses</option>
                                    <option value="Menunggu Kurir" <?= $o['status']=='Menunggu Kurir'?'selected':'' ?>>Ke Kurir</option>
                                    <option value="Selesai" <?= $o['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                                    <option value="Dibatalkan" <?= $o['status']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                        <td><a href="detail_pesanan.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-info text-white">Detail</a></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(!$orders) echo "<tr><td colspan='7' class='text-center'>Belum ada pesanan</td></tr>"; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>