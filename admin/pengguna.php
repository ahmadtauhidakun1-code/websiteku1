<?php
session_start(); require_once '../config/database.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

if (isset($_POST['ubah_role'])) { 
    $pdo->prepare("UPDATE users SET level=? WHERE id=?")->execute([$_POST['role_baru'], $_POST['user_id']]); 
}
if (isset($_GET['hapus'])) { 
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$_GET['hapus']]); 
}
$users = $pdo->query("SELECT * FROM users")->fetchAll();

if (isset($_GET['hapus_pelanggan'])) { 
    $pdo->prepare("DELETE FROM pelanggan WHERE id=?")->execute([$_GET['hapus_pelanggan']]); 
    header("Location: pengguna.php"); exit;
}
$customers = $pdo->query("SELECT * FROM pelanggan")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Kelola Pengguna</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
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
        <h4>Kelola Akses Staff (Admin, Kurir, Owner)</h4>
        <div class="table-responsive mb-5">
            <table class="table table-bordered bg-white shadow-sm mt-3 align-middle">
                <thead><tr><th>Nama Lengkap</th><th>Username</th><th>Level/Role Akses</th><th>Aksi Staff</th></tr></thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nama']) ?></td><td><?= htmlspecialchars($u['username']) ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                <select name="role_baru" class="form-select form-select-sm" style="width:130px;">
                                    <option value="admin" <?= $u['level']=='admin'?'selected':'' ?>>Admin</option>
                                    <option value="kurir" <?= $u['level']=='kurir'?'selected':'' ?>>Kurir</option>
                                    <option value="owner" <?= $u['level']=='owner'?'selected':'' ?>>Owner</option>
                                </select>
                                <button type="submit" name="ubah_role" class="btn btn-sm btn-primary">Ubah Role</button>
                            </form>
                        </td>
                        <td><a href="?hapus=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Staff?')">Hapus</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-4">Daftar Pelanggan Terdaftar</h4>
        <div class="table-responsive">
            <table class="table table-bordered bg-white shadow-sm mt-3 align-middle">
                <thead><tr><th>Nama Lengkap</th><th>Email</th><th>No WhatsApp</th><th>Aksi Pelanggan</th></tr></thead>
                <tbody>
                    <?php foreach($customers as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nama']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['no_telp']) ?></td>
                        <td><a href="?hapus_pelanggan=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Akun Pelanggan ini secara permanen?')">Hapus</a></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(!$customers) echo "<tr><td colspan='4' class='text-center'>Belum ada pelanggan terdaftar.</td></tr>"; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>