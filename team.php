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

            <div class="nav-left">
                <a href="index.php" class="nav-brand">
                    <img src="assets/logo.png" alt="Logo Strike Gear" class="nav-logo">
                </a>
            </div>

            <div class="nav-center" id="navContent">
                <a href="index.php">Home</a>
                <a href="kategori.php">Kategori</a>
                <a href="team.php">Our Team</a>
                <a href="#"
                    onclick="document.getElementById('aiChatbox').classList.toggle('active'); return false;">Chatbox</a>
            </div>

            <a href="kategori.php" class="nav-icon" title="Keranjang">
                <i class="bi bi-bag"></i></a>
            <a href="login.php" class="btn-login">Login</a>

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

</body>