-- Database: `catering_ukk`
CREATE DATABASE IF NOT EXISTS `catering_ukk`;
USE `catering_ukk`;

-- --------------------------------------------------------

-- Table structure for table `jenis_pembayarans`
CREATE TABLE IF NOT EXISTS `jenis_pembayarans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(50) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `atas_nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `pakets`
CREATE TABLE IF NOT EXISTS `pakets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_paket` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto1` varchar(255) DEFAULT NULL,
  `foto2` varchar(255) DEFAULT NULL,
  `foto3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `pelanggan`
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','owner','kurir') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`nama`, `username`, `password`, `level`) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Owner Catering', 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner'),
('Kurir 1', 'kurir1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kurir');

-- --------------------------------------------------------

-- Table structure for table `pemesanans`
CREATE TABLE IF NOT EXISTS `pemesanans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) NOT NULL,
  `tgl_pesan` datetime NOT NULL DEFAULT current_timestamp(),
  `tgl_acara` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('Menunggu Konfirmasi','Sedang Diproses','Menunggu Kurir','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `jenis_pembayaran_id` int(11) NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pelanggan_id` (`pelanggan_id`),
  KEY `jenis_pembayaran_id` (`jenis_pembayaran_id`),
  CONSTRAINT `pemesanans_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pemesanans_ibfk_2` FOREIGN KEY (`jenis_pembayaran_id`) REFERENCES `jenis_pembayarans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `detail_pemesanans`
CREATE TABLE IF NOT EXISTS `detail_pemesanans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pemesanan_id` int(11) NOT NULL,
  `paket_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pemesanan_id` (`pemesanan_id`),
  KEY `paket_id` (`paket_id`),
  CONSTRAINT `detail_pemesanans_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_pemesanans_ibfk_2` FOREIGN KEY (`paket_id`) REFERENCES `pakets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `pengirimans`
CREATE TABLE IF NOT EXISTS `pengirimans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pemesanan_id` int(11) NOT NULL,
  `kurir_id` int(11) NOT NULL,
  `tgl_kirim` datetime DEFAULT NULL,
  `status` enum('Menunggu','Sedang Dikirim','Tiba Ditujuan') NOT NULL DEFAULT 'Menunggu',
  `bukti_kirim` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pemesanan_id` (`pemesanan_id`),
  KEY `kurir_id` (`kurir_id`),
  CONSTRAINT `pengirimans_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengirimans_ibfk_2` FOREIGN KEY (`kurir_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
