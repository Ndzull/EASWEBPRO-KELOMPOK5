<?php
session_start();
include 'koneksi.php';

// Wajib login
if (!isset($_SESSION['status_login'])) {
    header("Location: loginpage.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$pesan_notif = "";

// PROSES UPLOAD FOTO PROFIL
if (isset($_POST['update_avatar'])) {
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['avatar_file']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['avatar_file']['name']);
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
            $target_file = $target_dir . $file_name;
            
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Simpan path gambar ke kolom foto_profil
                $conn->query("UPDATE users SET foto_profil = '$target_file' WHERE id = '$id_user'");
                $pesan_notif = "<div style='color: green; font-weight:bold; margin-bottom:15px;'>Foto profil berhasil diperbarui!</div>";
            }
        } else {
            $pesan_notif = "<div style='color: red; font-weight:bold; margin-bottom:15px;'>Format file tidak didukung! (Gunakan JPG/PNG)</div>";
        }
    }
}

// AMBIL DATA USER
$query_user = $conn->query("SELECT * FROM users WHERE id = '$id_user'");
$user = $query_user->fetch_assoc();
$nama_lengkap = $user['nama_depan'] . ' ' . $user['nama_belakang'];
// Panggil kolom foto_profil
$foto_profil = !empty($user['foto_profil']) ? $user['foto_profil'] : 'assets/ijulfoto.jpeg'; 

// AMBIL RIWAYAT PESANAN ASLI DARI DATABASE KITA
$query_transaksi = $conn->query("SELECT * FROM pesanan WHERE id_user = '$id_user' ORDER BY id_pesanan DESC");

// Data Statis Dummy (Voucher)
$vouchers = [
    ['title' => 'Gratis Ongkir Min. Rp0', 'desc' => 'Berlaku untuk semua ekspedisi', 'expiry' => '30 Juni 2026', 'available' => true],
    ['title' => 'Cashback 10%', 'desc' => 'Khusus kategori Joran', 'expiry' => '15 Juni 2026', 'available' => true],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Strike Gear</title>
    <link rel="stylesheet" href="style.css?v=23">
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        .profile-layout { display: flex; gap: 30px; margin-top: 20px; align-items: flex-start; }
        .col-kiri { flex: 3; }
        .col-kanan { flex: 7; }
        @media(max-width: 850px){ .profile-layout { flex-direction: column; } }
        
        .category-card { background-color: var(--color-surface); border: 3px solid var(--color-text-dark); border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 6px 6px 0px var(--color-text-dark); max-width: 100%; }
        .category-card h3 { font-family: 'Jockey One', sans-serif; color: var(--color-primary); border-bottom: 2px solid var(--color-text-dark); padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Avatar Neo-Brutalism */
        .avatar-box { width: 140px; height: 140px; border-radius: 12px; border: 3px solid var(--color-text-dark); box-shadow: 4px 4px 0px var(--color-text-dark); object-fit: cover; margin-bottom: 15px; background-color: white;}
        
        /* Biodata List */
        .bio-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 2px dashed var(--color-border); font-family: 'Segoe UI', sans-serif; font-size: 0.95rem;}
        .bio-item strong { color: var(--color-primary); text-align: right; }

        /* Table Riwayat */
        .admin-table { width: 100%; border-collapse: collapse; font-family: 'Segoe UI', sans-serif; }
        .admin-table th, .admin-table td { padding: 12px; border: 2px solid var(--color-text-dark); text-align: left; }
        .admin-table th { background-color: var(--color-primary); color: white; font-family: 'Jockey One', sans-serif; letter-spacing: 1px; }
        
        /* Voucher Neo-Brutal */
        .voucher-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .voucher-card { border: 3px solid var(--color-text-dark); border-radius: 8px; padding: 15px; background-color: #FCD34D; box-shadow: 4px 4px 0px var(--color-text-dark); }
        .voucher-card h4 { margin:0 0 5px 0; font-family: 'Jockey One', sans-serif; font-size:1.2rem;}
        @media(max-width: 600px){ .voucher-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <nav id="mainNavbar" class="navbar-section">
        <div class="nav-container">

            <div class="nav-left" style="display: flex; align-items: center; gap: 10px;">
                <a href="index.php" class="nav-brand">
                    <img src="assets/logo.png" alt="Logo Strike Gear" class="nav-logo">
                </a>
                <!-- <form action="profil.php" method="GET" class="search-form" style="display: flex; align-items: center;">
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
                </form> -->
            </div>

            <div class="nav-center" id="navContent">
                <a href="index.php">Home</a>
                <a href="kategori.php">Product</a>
                <a href="team.php">Team</a>
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

    <main class="category-section" style="padding-top: 100px;">
        <h2 class="header-title" style="margin-top:0;">Pengaturan Profil</h2>
        
        <?php echo $pesan_notif; ?>

        <div class="profile-layout">
            
            <div class="col-kiri">
                <div class="category-card" style="text-align: center;">
                    <img src="<?php echo htmlspecialchars($foto_profil); ?>" class="avatar-box" alt="Foto Profil">
                    <h3 style="border:none; margin-bottom:5px;"><?php echo htmlspecialchars($nama_lengkap); ?></h3>
                    <p style="font-family:'Segoe UI'; font-size:0.9rem; margin-bottom:15px;"><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <form action="" method="POST" enctype="multipart/form-data" id="formAvatar">
                        <input type="file" name="avatar_file" style="display:none;" id="fileInput" onchange="document.getElementById('formAvatar').submit();" accept=".jpg,.jpeg,.png">
                        <button type="button" class="btn-checkout" style="padding: 8px;" onclick="document.getElementById('fileInput').click();">Ubah Foto</button>
                        <input type="hidden" name="update_avatar" value="1">
                    </form>
                    
                    <div style="text-align:left; margin-top:30px;">
                        <h4 style="font-family: 'Jockey One', sans-serif; margin-bottom: 10px; border-bottom: 2px solid var(--color-text-dark); padding-bottom: 5px;">Informasi Pribadi</h4>
                        <div class="bio-item"><span>Lahir</span> <strong><?php echo htmlspecialchars($user['tanggal_lahir']); ?></strong></div>
                    </div>
                </div>
            </div>

            <div class="col-kanan">
                <div class="category-card">
                    <h3>Riwayat Pesanan Saya</h3>
                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total Tagihan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($query_transaksi->num_rows == 0): ?>
                                    <tr><td colspan="4" style="text-align:center;">Belum ada riwayat belanja.</td></tr>
                                <?php else: ?>
                                    <?php while($tx = $query_transaksi->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong>#ORD-<?php echo $tx['id_pesanan']; ?></strong></td>
                                            <td><?php echo date('d M Y', strtotime($tx['tanggal_pesan'])); ?></td>
                                            <td>Rp <?php echo number_format($tx['total_tagihan'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span style="background: <?php echo ($tx['status'] == 'Diproses') ? '#10B981' : (($tx['status'] == 'Ditolak') ? '#FCA5A5' : '#FCD34D'); ?>; 
                                                             color: <?php echo ($tx['status'] == 'Diproses') ? 'white' : 'var(--color-text-dark)'; ?>; 
                                                             padding:3px 8px; border:2px solid var(--color-text-dark); border-radius:4px; font-weight:bold; font-size:0.8rem;">
                                                    <?php echo htmlspecialchars($tx['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="category-card">
                    <h3>Voucher Spesial Member</h3>
                    <div class="voucher-grid">
                        <?php foreach($vouchers as $v): ?>
                            <div class="voucher-card">
                                <h4><?php echo $v['title']; ?></h4>
                                <p style="font-family:'Segoe UI'; font-size:0.85rem; margin-bottom:10px;"><?php echo $v['desc']; ?></p>
                                <button class="btn-checkout" style="background:var(--color-primary); padding:5px; font-size:0.9rem;" onclick="alert('Voucher berhasil diklaim!')">Klaim Voucher</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
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

</body>
</html>