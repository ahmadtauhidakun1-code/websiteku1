<?php
session_start(); require_once '../config/database.php';
if (isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier']; // bisa email (pelanggan) atau username (admin/kurir/owner)
    $password = $_POST['password'];

    // Cek di tabel pelanggan dulu (login dgn email)
    $stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE email = ?"); $stmt->execute([$identifier]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['user_role'] = 'pelanggan';
        header("Location: ../pelanggan/dashboard.php"); exit;
    } else {
        // Cek di tabel users (login dgn username untuk admin/owner/kurir)
        $stmt2 = $pdo->prepare("SELECT * FROM users WHERE username = ?"); $stmt2->execute([$identifier]);
        $staff = $stmt2->fetch();
        
        if ($staff && password_verify($password, $staff['password'])) {
            $_SESSION['user_id'] = $staff['id'];
            $_SESSION['user_nama'] = $staff['nama'];
            $_SESSION['user_role'] = $staff['level']; // enum: admin,owner,kurir
            
            if($staff['level'] == 'admin') header("Location: ../admin/dashboard.php");
            elseif($staff['level'] == 'owner') header("Location: ../owner/dashboard.php");
            elseif($staff['level'] == 'kurir') header("Location: ../kurir/dashboard.php");
            exit;
        } else {
            $error = "Email/Username atau Password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Login</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow-sm" style="width: 350px;">
    <h4 class="text-center mb-4 text-danger">Login CateringKu</h4>
    <?php if(isset($_GET['msg']) && $_GET['msg']=='registered') echo "<div class='alert alert-success'>Daftar berhasil! Silakan login.</div>"; ?>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="identifier" class="form-control mb-3" placeholder="Email / Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <button type="submit" class="btn btn-danger w-100 mb-2">Login</button>
    </form>
    <div class="text-center mt-3">
        <p class="mb-0">Pelanggan baru? <a href="register.php">Daftar Akun</a></p>
        <a href="../index.php" class="text-secondary small">&larr; Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>