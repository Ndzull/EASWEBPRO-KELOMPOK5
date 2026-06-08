<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $q_gambar = $conn->query("SELECT gambar FROM produk WHERE id='$id'");
    if ($q_gambar && $q_gambar->num_rows > 0) {
        $data_gambar = $q_gambar->fetch_assoc();
        if (file_exists("uploads/" . $data_gambar['gambar'])) {
            unlink("uploads/" . $data_gambar['gambar']);
        }
    }

    $delete = $conn->query("DELETE FROM produk WHERE id='$id'");
    
    if ($delete) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location.href='admin_produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus: " . $conn->error . "'); window.location.href='admin_produk.php';</script>";
    }
} else {
    header("Location: admin_produk.php");
}
?>