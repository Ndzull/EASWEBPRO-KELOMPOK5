<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginpage.php");
    exit;
}

$q_produk = $conn->query("SELECT COUNT(*) as total FROM produk");
$total_produk = $q_produk ? $q_produk->fetch_assoc()['total'] : 0;

$q_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='member'");
$total_member = $q_users ? $q_users->fetch_assoc()['total'] : 0;

$q_pesanan = $conn->query("SELECT COUNT(*) as total FROM pesanan");
$total_pesanan = $q_pesanan ? $q_pesanan->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Strike Gear</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=13">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="assets/logo.png" alt="Strike Gear Logo" class="sidebar-logo" style="width:70px; display:block; margin:0 auto 0px; filter: drop-shadow(0 0 0 #FFF) drop-shadow(0 0 15px #FFF);" />
            <h2>STRIKE GEAR</h2>
            <p><b>Admin Panel</b></p>   
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="admin_produk.php"><i class="fa-solid fa-box-open"></i> Kelola Produk</a></li>
            <li><a href="admin_pesanan.php"><i class="fa-solid fa-truck-fast"></i> Pesanan Masuk</a></li>
            <li><a href="admin_member.php"><i class="fa-solid fa-user-group"></i> Daftar Member</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content" style="margin-top: 15px;">
        <h1 class="header-title">Halo Bos <?php echo $_SESSION['nama_user']; ?>!</h1>
        
        <div class="dashboard-grid">
            <div class="summary-card">
                <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div class="card-info">
                    <h3><?php echo $total_produk; ?></h3>
                    <p>Total Produk</p>
                </div>
            </div>

            <div class="summary-card pesanan"> 
                <div class="card-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <div class="card-info">
                    <h3><?php echo $total_pesanan; ?></h3>
                    <p>Pesanan Masuk</p>
                </div>
            </div>

            <div class="summary-card member">
                <div class="card-icon"><i class="fa-solid fa-users"></i></div>
                <div class="card-info">
                    <h3><?php echo $total_member; ?></h3>
                    <p>Member Terdaftar</p>
                </div>
            </div>
        </div>

        <div class="instruction-panel">
            <h2>Instruksi Sistem</h2>
            <p>Selamat datang di Admin Dashboard Strike Gear. Pilih menu di sebelah kiri untuk mulai mengatur katalog toko.</p>
        </div>
    </main>

</body>
</html>