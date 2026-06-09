<?php
session_start();
include 'koneksi.php';

// Cek keamanan
if (!isset($_SESSION['status_login'])){
    header("Location: loginpage.php");
    exit;
}
if ($_SESSION['role'] == 'admin'){
    header("Location: admin_dashboard.php");
    exit;
}

// Ambil semua produk dari database yang stoknya masih ada
$query_produk = $conn->query("SELECT * FROM produk WHERE stok > 0 ORDER BY id_produk DESC");

if (isset($_GET['cari']) && $_GET['cari'] != '') {
    $kata_kunci = $conn->real_escape_string($_GET['cari']);
    $query_produk = $conn->query("SELECT * FROM produk WHERE stok > 0 AND (nama_produk LIKE '%$kata_kunci%' OR kategori LIKE '%$kata_kunci%') ORDER BY id_produk DESC");
} else {
    $query_produk = $conn->query("SELECT * FROM produk WHERE stok > 0 ORDER BY id_produk DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Produk - Toko Pancing</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="header-container">
        <div class="brand">
            <img src="assets/logo.png" alt="logo" class="logo">
            <h1>Strike Gear Collection</h1>
        </div>
        <div class="nav-right">
            <button class="hamburger-btn" onclick="toggleMenu()">☰</button>
            <a href="index.php" class="nav-home">Home</a>
            <div class="cart-icon">
                <a href="keranjang.php" style="text-decoration: none; color: inherit;">🛒 <span class="cart-count" id="cartBadge">0</span></a>
            </div>
        </div>
    </div>
</header>

<div class="sidebar-kategori" id="sidebarMenu">
    <button class="close-btn" onclick="toggleMenu()">✖</button>
    <h2>Pilih Kategori</h2>
    <ul>
        <li onclick="filterKategori('semua')">Semua Produk</li>
        <li onclick="filterKategori('joran')">Joran Pancing</li>
        <li onclick="filterKategori('reel')">Reel Pancing</li>
        <li onclick="filterKategori('benang')">Senar Pancing</li>
        <li onclick="filterKategori('kail')">Kail Pancing</li>
        <li onclick="filterKategori('umpan')">Umpan Pancing</li>
        <li onclick="filterKategori('aksesoris')">Aksesoris Pancing</li>
        <li onclick="filterKategori('bundle')">Bundle Pancing</li>
    </ul>
</div>

<main class="category-section">
    <h2 id="judul-halaman" class="category-title" style="margin-top: 2rem;">All Products</h2>
    
    <div class="category-grid" id="productGrid">
        <?php
        if ($query_produk && $query_produk->num_rows > 0) {
            while ($row = $query_produk->fetch_assoc()) {
                $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                // Mengambil gambar dari folder uploads yang dikelola admin
                $gambar = $row['gambar'] ? 'uploads/' . $row['gambar'] : 'https://via.placeholder.com/300?text=No+Image';
                
                echo '
                <div class="category-card product-card" data-kategori="' . strtolower($row['kategori']) . '">

                    <div class="product-image-wrapper">
                        <img src="' . $gambar . '" alt="' . htmlspecialchars($row['nama_produk']) . '" class="product-img" style="height:200px; object-fit:cover; width:100%;">
                        <div class="hover-specs">
                            <p class="specs-title">Deskripsi:</p>
                            <p style="font-size:14px; color:white; padding:0 10px; margin-bottom:10px;">' . htmlspecialchars(substr($row['deskripsi'], 0, 80)) . '...</p>
                            
                            <form action="proses_keranjang.php" method="POST" class="cart-action" style="display:flex; justify-content:center; gap:5px;">
                                <input type="hidden" name="id_produk" value="' . $row['id_produk'] . '">
                                <input type="number" name="jumlah" class="qty-input" value="1" min="1" max="' . $row['stok'] . '" style="width:60px; text-align:center;">
                                <button type="button" class="btn-add-cart" onclick="tambahKeranjang(this)">🛒 Tambah</button>
                            </form>
                        </div>
                    </div>
                    <h3>' . htmlspecialchars($row['nama_produk']) . '</h3>
                    <p class="product-price">' . $harga_rp . '</p>
                    <p style="font-size:12px; color:#666;">Sisa Stok: ' . $row['stok'] . '</p>
                </div>';
            }
        } else {
            echo "<div style='grid-column: 1 / -1; text-align: center; padding: 50px;'><h3>Belum ada produk di etalase.</h3></div>";
        }
        ?>
    </div>
</main>

<script>
    // JS Bawaan Temanmu untuk Visual Interaktif
    let jumlahKeranjang = 0;
    
    function tambahKeranjang(elemenTombol) {
        // Mengambil input jumlah yang ada di sebelah tombol
        let kotakInput = elemenTombol.parentElement.querySelector('.qty-input');
        let jumlahBeli = parseInt(kotakInput.value);
        let stokMaksimal = parseInt(kotakInput.getAttribute('max'));

        if (isNaN(jumlahBeli) || jumlahBeli < 1) {
            alert("Jumlah barang harus minimal 1!");
            kotakInput.value = 1; 
            return; 
        }

        if (jumlahBeli > stokMaksimal) {
            alert("Maaf, jumlah melebihi stok yang ada (" + stokMaksimal + ")");
            kotakInput.value = stokMaksimal;
            return;
        }

        // Update badge keranjang (Visual Only)
        jumlahKeranjang += jumlahBeli;
        document.getElementById('cartBadge').innerText = jumlahKeranjang;
        alert(jumlahBeli + " barang berhasil ditambahkan ke keranjang!");
        kotakInput.value = 1; 
    }

    function toggleMenu() {
        document.getElementById("sidebarMenu").classList.toggle("sidebar-active");
    }

    // Fungsi Filter Kategori
    function filterKategori(kategoriDipilih) {
        let semuaProduk = document.querySelectorAll('.product-card');
        let judulHalaman = document.getElementById('judul-halaman');
        
        // Update Judul Halaman sesuai kategori yang diklik
        if (kategoriDipilih === 'semua') judulHalaman.innerText = "All Products";
        else if (kategoriDipilih === 'joran') judulHalaman.innerText = "Kategori: Joran Pancing";
        else if (kategoriDipilih === 'reel') judulHalaman.innerText = "Kategori: Reel Pancing";
        else if (kategoriDipilih === 'benang') judulHalaman.innerText = "Kategori: Senar Pancing";
        else if (kategoriDipilih === 'kail') judulHalaman.innerText = "Kategori: Kail Pancing";
        else if (kategoriDipilih === 'umpan') judulHalaman.innerText = "Kategori: Umpan Pancing";
        else if (kategoriDipilih === 'aksesoris') judulHalaman.innerText = "Kategori: Aksesoris Pancing";
        else if (kategoriDipilih === 'bundle') judulHalaman.innerText = "Kategori: Bundle Pancing";

        // Filter produk yang ditampilkan
        semuaProduk.forEach(produk => {
            if (kategoriDipilih === 'semua') {
                produk.style.display = 'block'; 
            } else {
                if (produk.getAttribute('data-kategori') === kategoriDipilih) {
                    produk.style.display = 'block'; 
                } else {
                    produk.style.display = 'none';  
                }
            }
        });
        
        toggleMenu();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

</body>
</html>