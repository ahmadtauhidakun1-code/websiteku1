<?php
// pelanggan/proses_checkout.php
require_once '../config/database.php';

if (!isCustomerLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php');
}

$pelanggan_id = $_SESSION['pelanggan_id'];
$paket_id = $_POST['paket_id'];
$jumlah = $_POST['jumlah'];
$tgl_acara = $_POST['tgl_acara'];
$jenis_pembayaran_id = $_POST['jenis_pembayaran_id'];
$total_harga = $_POST['total_harga'];

// Validate minimum date (3 days from now)
// Note: In real app, add deeper validation here

$bukti_bayar = null;
if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION);
    $bukti_bayar = uniqid('bukti_') . '.' . $ext;
    move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], '../uploads/' . $bukti_bayar);
}

try {
    $pdo->beginTransaction();

    // Insert into pemesanans
    $stmt = $pdo->prepare("
        INSERT INTO pemesanans (pelanggan_id, tgl_acara, total_harga, jenis_pembayaran_id, bukti_bayar, status) 
        VALUES (?, ?, ?, ?, ?, 'Menunggu Konfirmasi')
    ");
    $stmt->execute([$pelanggan_id, $tgl_acara, $total_harga, $jenis_pembayaran_id, $bukti_bayar]);
    $pemesanan_id = $pdo->lastInsertId();

    // Get true price to prevent tampering
    $paket = $pdo->prepare("SELECT harga FROM pakets WHERE id = ?");
    $paket->execute([$paket_id]);
    $harga_satuan = $paket->fetchColumn();
    $subtotal = $harga_satuan * $jumlah;

    // Update total harga with true calculation to be safe
    $stmt = $pdo->prepare("UPDATE pemesanans SET total_harga = ? WHERE id = ?");
    $stmt->execute([$subtotal, $pemesanan_id]);

    // Insert into detail_pemesanans
    $stmt = $pdo->prepare("
        INSERT INTO detail_pemesanans (pemesanan_id, paket_id, jumlah, harga_satuan, subtotal) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$pemesanan_id, $paket_id, $jumlah, $harga_satuan, $subtotal]);

    $pdo->commit();
    $_SESSION['success'] = "Pesanan berhasil dibuat! Silakan pantau status pesanan Anda.";
    redirect('/pelanggan/detail_pesanan.php?id=' . $pemesanan_id);

}
catch (Exception $e) {
    $pdo->rollBack();
    die("Terjadi kesalahan: " . $e->getMessage());
}
