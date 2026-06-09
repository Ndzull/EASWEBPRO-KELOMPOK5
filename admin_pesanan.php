<?php
session_start();
include 'koneksi.php';

// Cek apakah yang akses benar-benar Admin
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Jika Admin menekan tombol ACC (Konfirmasi)
if (isset($_GET['acc'])) {
    $id_pesanan = $_GET['acc'];
    // Ubah status pesanan menjadi 'Diproses'
    $conn->query("UPDATE pesanan SET status = 'Diproses' WHERE id_pesanan = '$id_pesanan'");
    
    echo "<script>
            alert('Pesanan berhasil dikonfirmasi (ACC)! Status berubah menjadi Diproses.');
            window.location.href = 'admin_pesanan.php';
          </script>";
    exit;
}

// Jika Admin menekan tombol Tolak
if (isset($_GET['tolak'])) {
    $id_pesanan = $_GET['tolak'];
    // Ubah status pesanan menjadi 'Ditolak'
    $conn->query("UPDATE pesanan SET status = 'Ditolak' WHERE id_pesanan = '$id_pesanan'");
    
    echo "<script>
            alert('Pesanan telah ditolak.');
            window.location.href = 'admin_pesanan.php';
          </script>";
    exit;
}

// Ambil semua data pesanan, urutkan dari yang paling baru
$query_pesanan = $conn->query("SELECT * FROM pesanan ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Strike Gear</title>
    <link rel="stylesheet" href="style.css?v=20">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        .badge-status {
            padding: 5px 10px;
            border-radius: 6px;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.85rem;
            font-weight: bold;
            border: 2px solid var(--color-text-dark);
            box-shadow: 2px 2px 0px var(--color-text-dark);
            display: inline-block;
        }
        .status-menunggu { background-color: #FCD34D; color: #181D31; }
        .status-diproses { background-color: #10B981; color: white; }
        .status-ditolak { background-color: #FCA5A5; color: #181D31; }
    </style>
</head>
<body>

<div class="admin-body">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="assets/logo.png" alt="Logo" style="width: 60px; margin-bottom: 10px;">
            <h2>STRIKE GEAR</h2>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="admin_produk.php"><i class="fa-solid fa-box"></i> Kelola Produk</a></li>
            <li><a href="admin_pesanan.php" class="active"><i class="fa-solid fa-cart-shopping"></i> Pesanan Masuk</a></li>
            <li><a href="admin_member.php"><i class="fa-solid fa-users"></i> Data Member</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <h1 class="header-title">Daftar Pesanan Masuk</h1>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Nama Penerima</th>
                        <th>Metode & Kurir</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th>Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($query_pesanan && $query_pesanan->num_rows > 0) {
                        while ($row = $query_pesanan->fetch_assoc()) {
                            
                            // Menentukan warna badge berdasarkan status
                            $badge_class = 'status-menunggu';
                            if ($row['status'] == 'Diproses') $badge_class = 'status-diproses';
                            if ($row['status'] == 'Ditolak') $badge_class = 'status-ditolak';

                            echo "<tr>";
                            echo "<td><strong>#ORD-" . $row['id_pesanan'] . "</strong></td>";
                            echo "<td>" . date('d M Y, H:i', strtotime($row['tanggal_pesan'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_penerima']) . "<br><small>" . htmlspecialchars($row['kota_kabupaten']) . "</small></td>";
                            echo "<td>" . htmlspecialchars($row['metode_pembayaran']) . "<br><small>via " . htmlspecialchars($row['kurir']) . "</small></td>";
                            echo "<td><strong>Rp " . number_format($row['total_tagihan'], 0, ',', '.') . "</strong></td>";
                            
                            echo "<td><span class='badge-status {$badge_class}'>" . $row['status'] . "</span></td>";
                            
                            echo "<td>";
                            // Tampilkan tombol ACC dan Tolak HANYA jika statusnya masih "Menunggu Pembayaran"
                            if ($row['status'] == 'Menunggu Pembayaran') {
                                echo "<a href='admin_pesanan.php?acc=" . $row['id_pesanan'] . "' class='btn-action btn-success-brutal' onclick=\"return confirm('Yakin ingin meng-ACC pesanan ini?');\"><i class='fa-solid fa-check'></i> ACC</a> ";
                                echo "<a href='admin_pesanan.php?tolak=" . $row['id_pesanan'] . "' class='btn-action btn-delete' onclick=\"return confirm('Yakin ingin MENOLAK pesanan ini?');\"><i class='fa-solid fa-xmark'></i> Tolak</a>";
                            } else {
                                echo "<span style='font-size:12px; color:#666;'>Tindakan Selesai</span>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Belum ada pesanan masuk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>