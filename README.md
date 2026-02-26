# Catering Online (PHP Native)

Proyek Website E-Commerce Catering Online dengan PHP Native 8.2.12 dan MySQL (Tanpa Framework).

## Fitur Utama & Roles
1. **Pelanggan**: Bebas registrasi, melihat list paket, memesan, checkout, upload bukti bayar, pantau status pesanan, dan konfirmasi penerimaan pesanan.
2. **Admin**: Mengelola master data (Paket, Metode Pembayaran), mengelola pesanan masuk (konfirmasi pesanan, menugaskan kurir), melihat data pelanggan.
3. **Owner**: Melihat ringkasan pendapatan di dashboard, melihat laporan penjualan (dengan filter tanggal), dan cetak PDF/Print.
4. **Kurir**: Melihat daftar pengiriman bertugas, update status pengiriman, upload foto bukti pengiriman saat tiba di tujuan.

## Spesifikasi Teknis yang Digunakan
- PHP 8.2.12 (Support PDO & Modern PHP practices)
- MySQL / MariaDB (via XAMPP)
- Bootstrap 5 (via CDN) & Bootstrap Icons
- Keamanan: Menggunakan **Prepared Statements (PDO)** untuk perlindungan SQL Injection dan **password_hash() / password_verify()** untuk perlindungan Password. Validasi email dan pengisian form juga dilakukan di sisi server.
- File handling (Upload Foto Produk, Upload Bukti Transfer, Upload Bukti Pengiriman)

## Cara Menjalankan di Localhost (XAMPP)

1. Pastikan Anda telah menginstal **XAMPP** dengan versi PHP 8.2.x.
2. Clone atau ekstrak folder project ini ke dalam direktori `C:\xampp\htdocs\`. 
   *Contoh hasilnya:* `C:\xampp\htdocs\catering-ukk\`
3. Buka XAMPP Control Panel, lalu jalankan module **Apache** dan **MySQL**.
4. Buka browser dan akses **phpMyAdmin** di `http://localhost/phpmyadmin/`.
5. Buat database baru dengan nama: `catering_ukk`
6. Pilih database tersebut, masuk ke tab **Import**, lalu pilih file `database.sql` yang ada di root folder project ini dan klik **Go**.
7. Buka tab baru di browser dan akses website melalui: `http://localhost/catering-ukk/`

## Akses Akun Default (Staff)
Setelah mengimpor database, Anda bisa login menggunakan akun staff bawaan berikut:

- **Admin**
  - Username: `admin`
  - Password: `admin123`
- **Owner**
  - Username: `owner`
  - Password: `admin123`
- **Kurir**
  - Username: `kurir1`
  - Password: `admin123`

Untuk **Pelanggan**, silakan gunakan fitur Registrasi di halaman depan untuk membuat akun baru.

## Struktur Direktori Utama
- `/config`: Koneksi database PDO
- `/auth`: Logika Login, Register, Logout
- `/admin`, `/owner`, `/kurir`, `/pelanggan`: Masing-masing folder role
- `/includes`: Header & Footer global
- `/assets`: File statis pendukung (jika ada)
- `/uploads`: Tempat penyimpanan seluruh gambar yang diunggah
- `database.sql`: Skema dan data default database
- `index.php`: Landing page berisi katalog paket

---
*Dibuat untuk keperluan Uji Kompetensi Keahlian (UKK) / Ujian Praktek*
