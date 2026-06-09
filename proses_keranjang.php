<?php
session_start();
include 'koneksi.php';

// Cek keamanan
if (!isset($_SESSION['status_login']) || $_SESSION['role'] == 'admin') {
    echo "<script>alert('Harap login sebagai member terlebih dahulu!'); window.location.href='loginpage.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // TERSANGKA 1: Mengakomodasi perbedaan nama session ID
    // Cek apakah kamu pakai 'id_user' atau 'id' saat login
    $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : (isset($_SESSION['id']) ? $_SESSION['id'] : 0);
    
    if ($id_user == 0) {
        die("Error: Session ID User tidak ditemukan. Coba logout dan login kembali.");
    }

    $id_produk = $_POST['id_produk'];
    $jumlah_baru = $_POST['jumlah'];

    // TERSANGKA 2: Mencegah Fatal Error jika tabel tidak ada
    $cek_keranjang = $conn->query("SELECT * FROM keranjang WHERE id_user = '$id_user' AND id_produk = '$id_produk'");

    // Kalau query gagal (misal tabel belum ada), tampilkan errornya!
    if (!$cek_keranjang) {
        die("<h1>ERROR DATABASE:</h1><p>" . $conn->error . "</p><p>Apakah kamu sudah menjalankan perintah CREATE TABLE keranjang di phpMyAdmin?</p>");
    }

    if ($cek_keranjang->num_rows > 0) {
        // Jika barang sudah ada, tambahkan jumlahnya
        $data_lama = $cek_keranjang->fetch_assoc();
        $total_jumlah = $data_lama['jumlah'] + $jumlah_baru;
        $conn->query("UPDATE keranjang SET jumlah = '$total_jumlah' WHERE id_user = '$id_user' AND id_produk = '$id_produk'");
    } else {
        // Jika barang baru, masukkan ke keranjang
        $conn->query("INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ('$id_user', '$id_produk', '$jumlah_baru')");
    }

    // Berhasil! Lempar kembali ke etalase dengan pesan sukses
    echo "<script>
            alert('Mantap! Barang berhasil ditambahkan ke keranjang 🛒');
            window.location.href = 'kategori.php';
          </script>";
    exit;
} else {
    header("Location: kategori.php");
    exit;
}
?>