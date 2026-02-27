<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['user_role'];
    if ($role == 'admin') { header("Location: admin/dashboard.php"); exit; } 
    elseif ($role == 'pelanggan') { header("Location: pelanggan/dashboard.php"); exit; } 
    elseif ($role == 'kurir') { header("Location: kurir/dashboard.php"); exit; } 
    elseif ($role == 'owner') { header("Location: owner/dashboard.php"); exit; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CateringKu - Solusi Hidangan Terbaik</title>
    <!-- Template CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
        
        /* Navbar */
        .navbar-custom { background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.05); padding: 15px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; color: #ff6b6b !important; letter-spacing: -0.5px; }
        .nav-link { font-weight: 500; color: #2d3436 !important; margin: 0 10px; transition: 0.3s; }
        .nav-link:hover { color: #ff6b6b !important; }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            padding: 120px 0 80px;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        
        .hero-title { font-weight: 800; font-size: 3.5rem; color: #2d3436; line-height: 1.2; margin-bottom: 20px; }
        .hero-title span { color: #ff6b6b; position: relative; }
        .hero-subtitle { font-size: 1.1rem; color: #636e72; margin-bottom: 30px; line-height: 1.8; }
        
        .btn-custom-primary {
            background-color: #ff6b6b; color: white; border-radius: 30px; padding: 12px 30px; font-weight: 600; font-size: 1.1rem; border: none; transition: all 0.3s; box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
        }
        .btn-custom-primary:hover { background-color: #ff5252; color: white; transform: translateY(-3px); box-shadow: 0 15px 25px rgba(255, 107, 107, 0.4); }
        
        .btn-custom-outline {
            background-color: transparent; color: #ff6b6b; border: 2px solid #ff6b6b; border-radius: 30px; padding: 10px 30px; font-weight: 600; font-size: 1.1rem; transition: all 0.3s;
        }
        .btn-custom-outline:hover { background-color: #ff6b6b; color: white; }

        .hero-img { border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); transform: perspective(1000px) rotateY(-5deg); transition: 0.5s; }
        .hero-img:hover { transform: perspective(1000px) rotateY(0deg) scale(1.02); }

        /* Features Section */
        .features-section { padding: 80px 0; background-color: white; }
        .feature-card { padding: 30px; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.3s; text-align: center; height: 100%; border: 1px solid rgba(0,0,0,0.02); }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.08); }
        .icon-box { width: 70px; height: 70px; border-radius: 50%; background: #ffeaa7; color: #e17055; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px; }
        
        .feature-card:nth-child(2) .icon-box { background: #81ecec; color: #00b894; }
        .feature-card:nth-child(3) .icon-box { background: #74b9ff; color: #0984e3; }

        .feature-title { font-weight: 700; color: #2d3436; margin-bottom: 15px; font-size: 1.2rem; }
        .feature-desc { color: #636e72; font-size: 0.95rem; line-height: 1.6; }

        /* Footer */
        footer { background-color: #2d3436; color: white; padding: 20px 0; text-align: center; font-size: 0.9rem; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-utensils me-2"></i>CateringKu</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#keunggulan">Keunggulan</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a href="auth/login.php" class="btn btn-custom-outline me-2 w-100">Masuk</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a href="auth/register.php" class="btn btn-custom-primary w-100 px-4 py-2" style="font-size: 0.95rem;">Daftar Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="badge bg-danger text-white rounded-pill px-3 py-2 mb-3 shadow-sm d-inline-block">100% Halal & Higienis</div>
                    <h1 class="hero-title">Pesan Catering <span>Praktis</span> & <span>Lezat</span> untuk Setiap Acara</h1>
                    <p class="hero-subtitle">Dari makan siang kantor hingga acara syukuran besar, CateringKu menyediakan hidangan lezat dengan bahan berkualitas yang diantar tepat waktu langsung ke tempat Anda.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="auth/login.php" class="btn btn-custom-primary"><i class="fas fa-shopping-bag me-2"></i>Pesan Sekarang</a>
                        <a href="#keunggulan" class="btn btn-custom-outline"><i class="fas fa-arrow-down me-2"></i>Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1555243896-c709bfa0b564?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Catering Food" class="img-fluid hero-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="keunggulan" class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: #2d3436; font-size: 2.5rem;">Kenapa Memilih CateringKu?</h2>
                <div style="width: 60px; height: 4px; background: #ff6b6b; margin: 15px auto; border-radius: 2px;"></div>
                <p class="text-muted">Layanan primadona kesukaan semua lidah keluarga.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-star"></i></div>
                        <h4 class="feature-title">Kualitas Terjamin</h4>
                        <p class="feature-desc">Kami menggunakan bahan baku segar dan premium untuk memastikan rasa hidangan tetap lezat, sehat, dan menggugah selera.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-shipping-fast"></i></div>
                        <h4 class="feature-title">Pengiriman Tepat Waktu</h4>
                        <p class="feature-desc">Kurir kami memastikan pesanan diantarkan tepat waktu ke lokasi agar makanan Anda dapat dinikmati saat hangat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="fas fa-wallet"></i></div>
                        <h4 class="feature-title">Harga Terjangkau</h4>
                        <p class="feature-desc">Dapatkan paket terlengkap dengan fleksibilitas menu sesuai ukuran budget acara baik kecil maupun besar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="m-0">&copy; <?= date("Y") ?> CateringKu by Ahmad Tauhid. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>