<?php
// config/database.php

$host = '127.0.0.1';
$dbname = 'catering_ukk';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Return associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL configuration (adjust if your folder name is different)
$base_url = "http://localhost/catering-ukk";

/**
 * Helper function to redirect
 */
function redirect($url) {
    global $base_url;
    header("Location: " . $base_url . $url);
    exit();
}

/**
 * Check if logged in customer
 */
function isCustomerLoggedIn() {
    return isset($_SESSION['pelanggan_id']);
}

/**
 * Check if logged in staff (admin, owner, kurir)
 */
function isStaffLoggedIn($role = null) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['level'])) {
        return false;
    }
    if ($role && $_SESSION['level'] !== $role) {
        return false;
    }
    return true;
}

/**
 * Format currency to IDR
 */
function formatRupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
