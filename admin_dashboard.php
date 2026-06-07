<?php
session_start();

if (!isset($_SESSION['status_login'])) {
    header("Location: loginpage.php");
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>StrikeGear - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body style="background-color: #f4f4f4; text-align: center; font-family: sans-serif; padding-top: 50px;">
    <h1 style="color: blue;">Ruang Kerja Admin Strike Gear</h1>
    <h3>Halo Bos <?php echo $_SESSION['nama_user']; ?>! (Role: <?php echo $_SESSION['role']; ?>)</h3>
    <p>Di sini tempat untuk mengatur stok, tambah barang, dan cek laporan.</p>
    
    <a href="logout.php" style="color: red;">Logout</a>
</body>
</html>