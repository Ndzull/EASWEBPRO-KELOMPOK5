<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['status_login'])) {
    // Jika belum login, lempar ke halaman login
    header("Location: loginpage.php");
    exit;
}

// Jangan biarkan admin belanja
if ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$pesan = "";

// PROSES HAPUS BARANG DARI KERANJANG
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $conn->query("DELETE FROM keranjang WHERE id_keranjang = '$id_hapus' AND id_user = '$id_user'");
    header("Location: keranjang.php");
    exit;
}

// Ambil data keranjang dari database (JOIN dengan tabel produk untuk ambil nama & foto)
$query_keranjang = $conn->query("
    SELECT k.*, p.nama_produk, p.harga, p.gambar, p.stok 
    FROM keranjang k 
    JOIN produk p ON k.id_produk = p.id_produk 
    WHERE k.id_user = '$id_user'
");

$total_produk = 0;
$biaya_aplikasi = 2000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Strike Gear</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css?v=19">
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        .checkout-layout {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            align-items: flex-start;
            width: 100%; 
        }
        @media(max-width: 850px){
            .checkout-layout { flex-direction: column; }
            .sidebar-checkout { width: 100%; }
        }
        .main-content {
            flex: 7;
            width: 100%; 
        }
        .sidebar-checkout {
            flex: 3; 
            position: sticky;
            top: 100px;
            min-width: 320px; 
        }
        .category-card {
            background-color: var(--color-surface);
            border: 3px solid var(--color-text-dark);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 6px 6px 0px var(--color-text-dark);
        }
        .category-card h3 {
            font-family: 'Jockey One', sans-serif;
            color: var(--color-primary);
            border-bottom: 2px solid var(--color-text-dark);
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .category-section .category-card {
            max-width: 100%; 
            margin: 0 0 25px 0; 
        }
        .form-group label {
            font-family: 'Segoe UI', sans-serif;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid var(--color-text-dark);
            border-radius: 8px;
            font-family: 'Segoe UI', sans-serif;
            margin-bottom: 15px;
        }
        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px dashed var(--color-text-dark);
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border: 2px solid var(--color-text-dark);
            border-radius: 6px;
        }
        .btn-hapus {
            color: #d32f2f;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .btn-hapus:hover { text-decoration: underline; }
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-family: 'Segoe UI', sans-serif;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--color-primary);
            margin-top: 15px;
            border-top: 2px solid var(--color-text-dark);
            padding-top: 15px;
        }
        .btn-checkout {
            background: var(--color-primary);
            color: var(--color-text-light);
            border: 2px solid var(--color-text-dark);
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-family: 'Jockey One', sans-serif;
            font-size: 1.1rem;
            box-shadow: 4px 4px 0px var(--color-text-dark);
            transition: all 0.2s;
        }
        .btn-checkout:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-text-dark);
        }
    </style>
</head>
<body>

    <nav id="mainNavbar" class="navbar-section">
        <div class="nav-container">
            <div class="nav-left">
                <a href="index.php" class="nav-brand">
                    <img src="assets/logo.png" alt="Logo Strike Gear" class="nav-logo">
                </a>
            </div>
            <div class="nav-center" id="navContent">
                <a href="kategori.php">Product</a>
                <a href="team.php">Team</a>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="keranjang.php" class="nav-icon" style="text-decoration: none; font-size: 1.2rem;" title="Keranjang">🛒</a>
                <span style="font-family: 'Jockey One', sans-serif; color: var(--color-text-dark);">
                    Halo, <?php echo htmlspecialchars(explode(' ', trim($_SESSION['nama_user']))[0]); ?>!
                </span>
                <a href="logout.php" class="btn-login" style="background-color: #a86060; border-color: #181D31; color: white;">Logout</a>
            </div>
        </div>
    </nav>

    <main class="category-section" style="padding-top: 100px;">
        <div class="section-title" style="margin-top:0; padding-top:20px; margin-bottom: 20px;">
            <h2 style="font-size:2rem;">Keranjang Belanja</h2>
        </div>

        <?php if ($query_keranjang->num_rows == 0): ?>
            <div class="category-card" style="text-align: center; padding: 50px;">
                <h3 style="border:none;">Keranjangmu masih kosong 🎣</h3>
                <p>Yuk, cari alat pancing andalanmu di etalase!</p>
                <br>
                <a href="kategori.php" class="btn-checkout" style="text-decoration: none; padding: 10px 30px; display:inline-block; width:auto;">Mulai Belanja</a>
            </div>
        <?php else: ?>

        <form action="proses_pesanan.php" method="POST" id="form-checkout">
        <div class="checkout-layout">
            
            <div class="main-content">
                <div class="category-card">
                    <h3>Daftar Barang</h3>
                    <?php 
                    while ($baris = $query_keranjang->fetch_assoc()) {
                        $hitung = $baris['harga'] * $baris['jumlah'];
                        $total_produk += $hitung;
                        $gambar = $baris['gambar'] ? 'uploads/' . $baris['gambar'] : 'https://via.placeholder.com/60';
                    ?>
                        <div class="item-row">
                            <div class="item-info">
                                <img src="<?php echo $gambar; ?>" class="item-img" alt="Foto">
                                <div>
                                    <p style="margin:0; font-weight:bold; font-family:'Segoe UI';"><?php echo htmlspecialchars($baris['nama_produk']); ?></p>
                                    <p style="margin:0; font-size:0.85rem;">Rp<?php echo number_format($baris['harga'], 0, ',', '.'); ?> x <?php echo $baris['jumlah']; ?> Pcs</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin:0; font-weight:bold; color:var(--color-primary);">Rp<?php echo number_format($hitung, 0, ',', '.'); ?></p>
                                <a href="keranjang.php?hapus=<?php echo $baris['id_keranjang']; ?>" class="btn-hapus" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="category-card">
                    <h3>Informasi Pengiriman</h3>
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($_SESSION['nama_user']); ?>" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Nomor Telepon / WA</label>
                            <input type="text" name="telepon" placeholder="Contoh: 08123456789" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" placeholder="Nama Jalan, RT/RW, Patokan Rumah..." required></textarea>
                    </div>
                    <div style="display: flex; gap: 20px;">
                        <div class="form-group" style="flex: 2;">
                            <label>Kota</label>
                            <input type="text" name="kota" placeholder="Contoh: Surabaya" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Kode Pos</label>
                            <input type="text" name="kodepos" placeholder="60111" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-checkout">
                <div class="category-card">
                    <h3>Pembayaran</h3>
                    <div class="form-group">
                        <label>Pilih Kurir</label>
                        <select name="kurir" id="pilihan_kurir" onchange="hitungTotal()" required>
                            <option value="" disabled selected hidden>-- Pilih Kurir --</option>
                            <option value="JNT" data-harga="15000">J&T Reguler - Rp15.000</option>
                            <option value="JNE" data-harga="23000">JNE Express - Rp23.000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="pembayaran" required>
                            <option value="" disabled selected hidden>-- Pilih Pembayaran --</option>
                            <option value="Transfer">Transfer Bank (BCA/Mandiri/BRI)</option>
                            <option value="COD">Bayar di Tempat (COD)</option>
                            <option value="E-Wallet">E-Wallet (GoPay/OVO/Dana)</option>
                        </select>
                    </div>

                    <h3 style="margin-top:30px;">Ringkasan Belanja</h3>
                    <div class="price-row">
                        <span>Subtotal Produk</span>
                        <span>Rp<?php echo number_format($total_produk, 0, ',', '.'); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Ongkos Kirim</span>
                        <span id="tampil_ongkir">Rp0</span>
                    </div>
                    <div class="price-row">
                        <span>Biaya Aplikasi</span>
                        <span>Rp<?php echo number_format($biaya_aplikasi, 0, ',', '.'); ?></span>
                    </div>
                    <div class="price-row total-row">
                        <span>Total Bayar</span>
                        <span id="tampil_total">Rp<?php echo number_format($total_produk + $biaya_aplikasi, 0, ',', '.'); ?></span>
                    </div>

                    <input type="hidden" name="total_bayar" id="input_total_bayar" value="<?php echo $total_produk + $biaya_aplikasi; ?>">
                    
                    <br>
                    <button type="submit" name="checkout" class="btn-checkout">Buat Pesanan</button>
                </div>
            </div>
        </div>
        </form>

        <?php endif; ?>
    </main>

    <footer class="footer-section">
        <div class="footer-body">
            <div class="footer-col">
                <p class="footer-brand-title">Strike Gear's<br>Marketplace</p>
                <p class="footer-brand-sub">Gear terbaik untuk angler sejati</p>
            </div>
            <div class="footer-col">
                <p class="footer-heading">Find Us!</p>
                <p class="footer-address">Jl. Raya ITS, Sukolilo,<br>Surabaya 60111</p>
            </div>
        </div>
        <div class="footer-bottom">
            <span class="footer-copy">©Strike Gear 2026</span>
        </div>
    </footer>

    <script>
    function hitungTotal() {
        var select = document.getElementById('pilihan_kurir');
        var hargaOngkir = parseInt(select.options[select.selectedIndex].getAttribute('data-harga')) || 0;
        var subtotalProduk = <?php echo $total_produk; ?>;
        var biayaAplikasi = 2000;
        var totalBayar = subtotalProduk + hargaOngkir + biayaAplikasi;
        
        document.getElementById('tampil_ongkir').innerText = 'Rp' + hargaOngkir.toLocaleString('id-ID').replace(/,/g, '.');
        document.getElementById('tampil_total').innerText = 'Rp' + totalBayar.toLocaleString('id-ID').replace(/,/g, '.');
        document.getElementById('input_total_bayar').value = totalBayar;
    }
    </script>
</body>
</html>