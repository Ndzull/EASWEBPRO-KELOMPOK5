<?php
session_start();

if (!isset($_SESSION['status_login'])) {
    header("Location: loginpage.php");
    exit;
}

if ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>StrikeGear - Dashboard</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body style="text-align: center; font-family: sans-serif; padding-top: 50px;">
    <h1>Selamat Datang di Strike Gear!</h1>
    <h3>Halo, <?php echo $_SESSION['nama_user']; ?> (Role: <?php echo $_SESSION['role']; ?>)</h3>
    <p>Ini adalah halaman katalog sepatu untuk pembeli.</p>
    
    <a href="logout.php" style="color: red;">Logout</a>
</body>
</html>