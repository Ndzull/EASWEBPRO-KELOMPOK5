<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Pancing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>

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
                <a href="kategori.php">Product</a>
                <!-- <a href="team.php">Team</a> -->
                <a href="#" onclick="document.getElementById('aiChatbox').classList.toggle('active'); return false;">Chatbox</a>
                </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="keranjang.php" class="nav-icon" style="text-decoration: none; font-size: 1.2rem;" title="Keranjang">🛒</a>
                
                <?php if (isset($_SESSION['status_login'])): ?>
                    <?php 
                        // Ambil nama depan
                        $nama_depan = explode(' ', trim($_SESSION['nama_user']))[0]; 
                        
                        // Tarik foto profil dari database khusus untuk navbar
                        $id_user_nav = $_SESSION['id_user'];
                        $query_nav = $conn->query("SELECT foto_profil FROM users WHERE id = '$id_user_nav'");
                        $data_nav = $query_nav->fetch_assoc();
                        $foto_nav = !empty($data_nav['foto_profil']) ? $data_nav['foto_profil'] : 'assets/ijulfoto.jpeg';
                    ?>
                    <span style="font-family: 'Jockey One', sans-serif; color: var(--color-text-dark);">Halo, <?php echo htmlspecialchars($nama_depan); ?>!</span>
                    
                    <a href="profil.php" title="Profil Saya" style="display: flex; align-items: center;">
                        <img src="<?php echo htmlspecialchars($foto_nav); ?>" alt="Avatar" style="
                            width: 38px; 
                            height: 38px; 
                            border-radius: 50%; 
                            object-fit: cover; 
                            border: 2px solid var(--color-text-dark); 
                            background-color: white;
                            cursor: pointer;
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


    <!-- Our Team -->
    <div class="section-title anim anim-5">
        <h2>Our Team</h2>
        <p>Orang-orang kalcer di balik Streak Gear</p>
    </div>

    <div class="team-grid anim anim-5">
        <div class="team-card">
            <img src="assets/ijulfoto.jpeg" alt="Naila" />
            <div class="team-info">
                <div class="team-name">Naila Dzulfa</div>
                <div class="team-btns">
                    <a class="team-btn" href="https://www.linkedin.com/in/nailadzulfa">☍ LinkedIn</a>
                    <a class="team-btn" href="mailto:naiidzull56@gmail.com">✉ Email</a>
                </div>
            </div>
        </div>
        <div class="team-card">
            <img src="assets/hanifoto.jpeg" alt="Hani" />
            <div class="team-info">
                <div class="team-name">Hanida Hafsya Tsabita</div>
                <div class="team-btns">
                    <a class="team-btn" href="https://www.linkedin.com/in/hanida-hafsya-tsabita-42b849380/">☍
                        LinkedIn</a>
                    <a class="team-btn" href="mailto:hanidahafsya@gmail.com">✉ Email</a>
                </div>
            </div>
        </div>
        <div class="team-card">
            <img src="assets/gracefoto.jpeg" alt="Grace" />
            <div class="team-info">
                <div class="team-name">Grace Nathalie</div>
                <div class="team-btns">
                    <a class="team-btn" href="https://www.linkedin.com/in/grace-nathalie-diandra-8ba460315/">☍
                        LinkedIn</a>
                    <a class="team-btn" href="mailto:gracechristy96@gmail.com">✉ Email</a>
                </div>
            </div>
        </div>
        <div class="team-card">
            <img src="assets/ikhwanfoto.jpeg" alt="Ikhwan" />
            <div class="team-info">
                <div class="team-name">Ikhwan Nursyabani</div>
                <div class="team-btns">
                    <a class="team-btn" href="https://www.linkedin.com/in/ikhwan-nur-sya-bani-666231343">☍ LinkedIn</a>
                    <a class="team-btn" href="mailto:nursyabaniikhwan@gmail.com">✉ Email</a>
                </div>
            </div>
        </div>
        <div class="team-card">
            <img src="assets/kikifoto.jpeg" alt="Kiki" />
            <div class="team-info">
                <div class="team-name">Rizki Ritama</div>
                <div class="team-btns">
                    <a class="team-btn" href="https://www.linkedin.com/in/rizki-ritama-43b7783a9">☍ LinkedIn</a>
                    <a class="team-btn" href="mailto:rizkiritama27@gmail.com">✉ Email</a>
                </div>
            </div>
        </div>
    </div>

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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="kategori.php">Kategori</a></li>
                    <!-- <li><a href="team.php">Team</a></li> -->
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

    

</body>