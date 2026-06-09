<?php
session_start();
include 'koneksi.php';

// Cek keamanan
if (!isset($_SESSION['status_login']) || $_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['checkout'])) {
    $id_user = $_SESSION['id_user'];
    
    // Menangkap data dari form keranjang.php
    $nama_penerima     = $_POST['nama'];
    $nomor_telepon     = $_POST['telepon'];
    $alamat_lengkap    = $_POST['alamat'];
    $kota_kabupaten    = $_POST['kota'];
    $kode_pos          = $_POST['kodepos'];
    $kurir             = $_POST['kurir'];
    $metode_pembayaran = $_POST['pembayaran'];
    $total_tagihan     = $_POST['total_bayar'];
    
    // Hitung otomatis biaya tambahan
    $biaya_jasa = 2000;
    $biaya_pengiriman = 0;
    if ($kurir == 'JNT') $biaya_pengiriman = 15000;
    if ($kurir == 'JNE') $biaya_pengiriman = 23000;

    $tanggal_pesan = date("Y-m-d H:i:s");

    // 1. SIMPAN KE TABEL PESANAN (Sesuai draft SQL Ijul)
    $query_pesanan = "INSERT INTO pesanan (id_user, nama_penerima, nomor_telepon, alamat_lengkap, kota_kabupaten, kode_pos, kurir, metode_pembayaran, biaya_pengiriman, biaya_jasa, total_tagihan, status, tanggal_pesan) 
                      VALUES ('$id_user', '$nama_penerima', '$nomor_telepon', '$alamat_lengkap', '$kota_kabupaten', '$kode_pos', '$kurir', '$metode_pembayaran', '$biaya_pengiriman', '$biaya_jasa', '$total_tagihan', 'Menunggu Pembayaran', '$tanggal_pesan')";
    
    if ($conn->query($query_pesanan)) {
        $id_pesanan_baru = $conn->insert_id;

        // 2. PINDAHKAN BARANG DARI KERANJANG KE DETAIL PESANAN
        $ambil_keranjang = $conn->query("SELECT k.*, p.harga FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.id_user = '$id_user'");
        
        while ($baris = $ambil_keranjang->fetch_assoc()) {
            $id_produk    = $baris['id_produk'];
            $jumlah_beli  = $baris['jumlah'];
            $harga_satuan = $baris['harga']; // Diambil dari tabel produk

            // Simpan sesuai draft detail_pesanan milikmu
            $conn->query("INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah_beli, harga_satuan) 
                          VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah_beli', '$harga_satuan')");
        }

        // 3. KOSONGKAN KERANJANG
        $conn->query("DELETE FROM keranjang WHERE id_user = '$id_user'");

        echo "<script>
                alert('Pesanan Berhasil Dibuat! Silakan tunggu konfirmasi Admin.');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal memproses pesanan: " . $conn->error . "'); window.history.back();</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>