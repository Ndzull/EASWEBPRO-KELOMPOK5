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
    <link rel="stylesheet" href="style.css?v=24">
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=17"> <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        body { padding-top: 30px; }
        
        .sidebar-kategori { z-index: 2000; } 
        
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content {
            display: none; 
            position: absolute; 
            background-color: var(--color-surface);
            min-width: 180px; 
            box-shadow: 4px 4px 0px var(--color-text-dark);
            border: 3px solid var(--color-text-dark); 
            z-index: 1000;
            border-radius: 8px; 
            overflow: hidden; 
            top: 100%; 
            left: 0;
            margin-top: 5px;
        }
        .dropdown-content a {
            color: var(--color-text-dark); 
            padding: 12px 16px; 
            text-decoration: none;
            display: block; 
            border-bottom: 2px solid var(--color-text-dark);
            font-family: 'Segoe UI', sans-serif; 
            font-size: 0.95rem; 
            font-weight: bold;
            transition: all 0.2s;
        }
        .dropdown-content a:last-child { border-bottom: none; }
        .dropdown-content a:hover { 
            background-color: var(--color-primary); 
            color: white; 
            padding-left: 20px; /* Efek bergeser saat dihover */
        }
        .dropdown:hover .dropdown-content { display: block; }
        .hover-specs {
            text-align: left;
        }
    </style>
</head>
<body>

    <nav id="mainNavbar" class="navbar-section">
        <div class="nav-container">

            <div class="nav-left" style="display: flex; align-items: center; gap: 10px;">
                <a href="index.php" class="nav-brand">
                    <img src="assets/logo.png" alt="Logo Strike Gear" class="nav-logo">
                </a>
                <form action="kategori.php" method="GET" class="search-form" style="display: flex; align-items: center;">
                    <input type="text" name="cari" placeholder="Cari Produk..." style="
                        padding: 6px 12px;
                        border: 2px solid var(--color-text-dark);
                        border-radius: 6px 0 0 6px;
                        font-family: 'Playfair Display', sans-serif;
                        font-size: 0.9rem;
                        outline: none;
                        width: 180px;
                    ">
                    <button type="submit" style="
                        background-color: var(--color-primary);
                        color: var(--color-text-light);
                        border: 2px solid var(--color-text-dark);
                        border-left: none;
                        border-radius: 0 6px 6px 0;
                        padding: 6px 12px;
                        cursor: pointer;
                        font-size: 0.9rem;
                    "><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="nav-center" id="navContent">
                <a href="index.php">Home</a>
                
                <div class="dropdown">
                    <a href="#" style="text-decoration:none;">Kategori ▼</a>
                    <div class="dropdown-content">
                        <a href="#" onclick="filterKategori('semua', false); return false;">Semua Produk</a>
                        <a href="#" onclick="filterKategori('joran', false); return false;">Joran Pancing</a>
                        <a href="#" onclick="filterKategori('reel', false); return false;">Reel Pancing</a>
                        <a href="#" onclick="filterKategori('benang', false); return false;">Senar Pancing</a>
                        <a href="#" onclick="filterKategori('kail', false); return false;">Kail Pancing</a>
                        <a href="#" onclick="filterKategori('umpan', false); return false;">Umpan Pancing</a>
                        <a href="#" onclick="filterKategori('aksesoris', false); return false;">Aksesoris</a>
                        <a href="#" onclick="filterKategori('bundle', false); return false;">Bundle Pancing</a>
                    </div>
                </div>

                <a href="team.php">Team</a>
                <a href="#" onclick="document.getElementById('aiChatbox').classList.toggle('active'); return false;">Chatbox</a>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="keranjang.php" class="nav-icon" style="text-decoration: none; font-size: 1.2rem;" title="Keranjang">🛒</a>
                
                <?php if (isset($_SESSION['status_login'])): ?>
                    <?php 
                        $nama_depan = explode(' ', trim($_SESSION['nama_user']))[0]; 
                        $id_user_nav = $_SESSION['id_user'];
                        $query_nav = $conn->query("SELECT foto_profil FROM users WHERE id = '$id_user_nav'");
                        $data_nav = $query_nav->fetch_assoc();
                        $foto_nav = !empty($data_nav['foto_profil']) ? $data_nav['foto_profil'] : 'assets/ijulfoto.jpeg';
                    ?>
                    <span style="font-family: 'Jockey One', sans-serif; color: var(--color-text-dark);">Halo, <?php echo htmlspecialchars($nama_depan); ?>!</span>
                    
                    <a href="profil.php" title="Profil Saya" style="display: flex; align-items: center;">
                        <img src="<?php echo htmlspecialchars($foto_nav); ?>" alt="Avatar" style="
                            width: 38px; height: 38px; border-radius: 50%; object-fit: cover; 
                            border: 2px solid var(--color-text-dark); background-color: white; cursor: pointer;
                        ">
                    </a>
                    <a href="logout.php" class="btn-logout-nav">Logout</a>
                <?php else: ?>
                    <a href="loginpage.php" class="btn-login">Login</a>
                <?php endif; ?>
            </div>

            <button class="navbar-toggler" id="navToggler">
                <i class="bi bi-list"></i>
            </button>

        </div>
    </nav>

<div class="sidebar-kategori" id="sidebarMenu">
    <button class="close-btn" onclick="toggleMenu()">✖</button>
    <h2>Pilih Kategori</h2>
    <ul>
        <li onclick="filterKategori('semua', true)">Semua Produk</li>
        <li onclick="filterKategori('joran', true)">Joran Pancing</li>
        <li onclick="filterKategori('reel', true)">Reel Pancing</li>
        <li onclick="filterKategori('benang', true)">Senar Pancing</li>
        <li onclick="filterKategori('kail', true)">Kail Pancing</li>
        <li onclick="filterKategori('umpan', true)">Umpan Pancing</li>
        <li onclick="filterKategori('aksesoris', true)">Aksesoris Pancing</li>
        <li onclick="filterKategori('bundle', true)">Bundle Pancing</li>
    </ul>
</div>

<main class="category-section">
    <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 40px; width: 100%;">
        <!-- <button class="hamburger-btn" onclick="toggleMenu()" style="background:var(--color-primary); color:white; border:2px solid var(--color-text-dark); padding:5px 15px; border-radius:8px; cursor:pointer; font-weight:bold; box-shadow: 2px 2px 0px var(--color-text-dark);">☰ Filter Kategori</button> -->
        <h2 id="judul-halaman" class="category-title" style="margin: 0; text-align: center; width: 100%;">All Products</h2>
    </div>
    
    <div class="category-grid" id="productGrid">
        <?php
        if ($query_produk && $query_produk->num_rows > 0) {
            while ($row = $query_produk->fetch_assoc()) {
                $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                $gambar = $row['gambar'] ? 'uploads/' . $row['gambar'] : 'https://via.placeholder.com/300?text=No+Image';
                
                echo '
                <div class="category-card product-card" data-kategori="' . strtolower($row['kategori']) . '" style="text-align:left;">

                    <div class="product-image-wrapper" style="text-align:left;">
                        <img src="' . $gambar . '" alt="' . htmlspecialchars($row['nama_produk']) . '" class="product-img" style="height:200px; object-fit:cover; width:100%;">
                        <div class="hover-specs" style="text-align:left;">
                            <p class="specs-title">Deskripsi:</p>
                                <p style="font-size:14px; color:black; padding:0 10px; margin-bottom:10px; text-align: left !important;">' . htmlspecialchars(substr($row['deskripsi'], 0, 80)) . '</p>
                                
                            <form action="proses_keranjang.php" method="POST" class="cart-action" style="display:flex; justify-content:center; gap:5px;">
                                <input type="hidden" name="id_produk" value="' . $row['id_produk'] . '">
                                <input type="number" name="jumlah" class="qty-input" value="1" min="1" max="' . $row['stok'] . '" style="width:60px; text-align:center;">
                                <button type="button" class="btn-add-cart" onclick="tambahKeranjang(this)">🛒 Tambah</button>
                            </form>
                        </div>
                    </div>
                    <h3 style="text-align:left;">' . htmlspecialchars($row['nama_produk']) . '</h3>
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
<footer class="footer-section">
        <div class="footer-body">

            <div class="footer-col">
                <p class="footer-brand-title">Strike Gear's<br>Marketplace</p>
                <p class="footer-brand-sub">Gear terbaik untuk angler sejati</p>
                <div class="footer-socials">
                    <a href="https://discord.gg/DRYXJXCwT" class="social-btn"><i class="bi bi-discord"></i></a>
                    <a href="https://wa.me/6285233679797?text=Halo%20Strike%20Gear,%20saya%20mau%20tanya!" class="social-btn"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <p class="footer-heading">Quick Links</p>
                <ul class="footer-links">
                    <!-- <li><a href="index.php">Home</a></li> -->
                    <li><a href="kategori.php">Kategori</a></li>
                    <li><a href="team.php">Team</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#" onclick="document.getElementById('aiChatbox').classList.toggle('active'); return false;">Chatbox</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <p class="footer-heading">Find Us!</p>
                <p class="footer-org">Strike Gear</p>
                <p class="footer-address">
                    Jl. Raya ITS, Sukolilo,<br>
                    Surabaya 60111
                </p>
                <div class="footer-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.691977073289!2d112.79467261477508!3d-7.275841894748135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa10ea2ae883%3A0xbe22c41f60b19294!2sInstitut%20Teknologi%20Sepuluh%20Nopember!5e0!3m2!1sen!2sid!4v1686280000000!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <span class="footer-copy">©Strike Gear 2026</span>
        </div>
    </footer>

    <div id="aiChatbox" class="chatbox-sidebar">
        <div class="chatbox-header">
            <h5 class="m-0">Strike Gear ChatBox</h5>
            <button onclick="toggleChat()" class="btn-close btn-close-white"></button>
        </div>
        <div id="chatBody" class="chatbox-body">
            <div class="msg ai-msg">Halo! Ada yang bisa saya bantu seputar alat pancing? 🎣</div>
        </div>
        <div class="chatbox-footer">
            <input type="text" id="userInput" class="form-control" placeholder="Ketik pertanyaan...">
            <button onclick="sendMessage()" class="send-btn">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

<script>
    function tambahKeranjang(elemenTombol) {
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

        elemenTombol.closest('form').submit();
    }

    function toggleMenu() {
        document.getElementById("sidebarMenu").classList.toggle("sidebar-active");
    }

    function filterKategori(kategoriDipilih, dariSidebar) {
    const semuaProduk = document.querySelectorAll('.product-card');
    const judulHalaman = document.getElementById('judul-halaman');
    
    const daftarJudul = {
        'semua': 'All Products',
        'joran': 'Kategori: Joran Pancing',
        'reel': 'Kategori: Reel Pancing',
        'benang': 'Kategori: Senar Pancing',
        'kail': 'Kategori: Kail Pancing',
        'umpan': 'Kategori: Umpan Pancing',
        'aksesoris': 'Kategori: Aksesoris Pancing',
        'bundle': 'Kategori: Bundle Pancing'
    };
    if (judulHalaman) judulHalaman.innerText = daftarJudul[kategoriDipilih] || 'All Products';

    semuaProduk.forEach(produk => {
        if (kategoriDipilih === 'semua' || produk.getAttribute('data-kategori') === kategoriDipilih) {
            produk.style.display = 'block'; 
        } else {
            produk.style.display = 'none';  
        }
    });

    const dropdown = document.querySelector('.dropdown-content');
    if (dropdown) {
        dropdown.style.display = 'none';
        setTimeout(() => { dropdown.style.display = ''; }, 200);
    }
    if (dariSidebar === true) {
        toggleMenu();
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

</body>
</html> 