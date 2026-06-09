-- Ini draft input SQL yang dipakai di myphpadmin
-- Tabel User
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_depan VARCHAR(50) NOT NULL,
    nama_belakang VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    role ENUM('admin', 'member') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabel Produk
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL
);

-- Tabel Pesanan Utama
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nama_penerima VARCHAR(100) NOT NULL,
    nomor_telepon VARCHAR(20) NOT NULL,
    alamat_lengkap TEXT NOT NULL,
    kota_kabupaten VARCHAR(100) NOT NULL,
    kode_pos VARCHAR(10) NOT NULL,
    kurir VARCHAR(50) NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    biaya_pengiriman INT NOT NULL,
    biaya_jasa INT NOT NULL,
    total_tagihan INT NOT NULL,
    status VARCHAR(50) DEFAULT 'Menunggu Pembayaran',
    tanggal_pesan TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Detail Pesanan (Isi Keranjang)
CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah_beli INT NOT NULL,
    harga_satuan INT NOT NULL
);