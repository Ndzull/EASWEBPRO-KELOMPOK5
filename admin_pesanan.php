<?php
session_start();
include 'koneksi.php';

// ===================================================
// SECURITY CHECK: HANYA ADMIN YANG BOLEH MASUK
// ===================================================
if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// ===================================================
// LOGIKA PROSES ACC / KONFIRMASI PESANAN
// ===================================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'konfirmasi') {
    $id_pesanan = $_GET['id'];

    // 1. Cek status pesanan saat ini untuk keamanan ganda
    $cek_pesanan = $conn->query("SELECT status FROM pesanan WHERE id_pesanan = '$id_pesanan'");
    if ($cek_pesanan && $cek_pesanan->num_rows > 0) {
        $data_pesanan = $cek_pesanan->fetch_assoc();

        // Hanya proses jika statusnya masih 'Menunggu Konfirmasi'
        if ($data_pesanan['status'] == 'Menunggu Konfirmasi') {
            
            // 2. Update status pesanan menjadi 'Pesanan Terkonfirmasi'
            $update_status = $conn->query("UPDATE pesanan SET status = 'Pesanan Terkonfirmasi' WHERE id_pesanan = '$id_pesanan'");

            if ($update_status) {
                // 3. Ambil rincian produk yang dibeli dari tabel detail_pesanan
                $ambil_detail = $conn->query("SELECT id_produk, jumlah_beli FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'");
                
                while ($detail = $ambil_detail->fetch_assoc()) {
                    $id_produk   = $detail['id_produk'];
                    $jumlah_beli = $detail['jumlah_beli'];

                    // 4. Kurangi stok produk di tabel produk secara otomatis
                    $conn->query("UPDATE produk SET stok = stok - $jumlah_beli WHERE id_produk = '$id_produk'");
                }

                echo "<script>
                        alert('Pesanan Berhasil Dikonfirmasi! Stok produk otomatis dikurangi.');
                        window.location.href = 'admin_pesanan.php';
                      </script>";
                exit;
            }
        }
    }
}

// Mengambil semua data pesanan masuk
$query_pesanan = "SELECT * FROM pesanan ORDER BY id_pesanan DESC";
$result_pesanan = $conn->query($query_pesanan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeGear - Pesanan Masuk</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=14">
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
            <li><a href="admin_dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="admin_produk.php"><i class="fa-solid fa-box-open"></i> Kelola Produk</a></li>
            <li><a href="admin_pesanan.php" class="active"><i class="fa-solid fa-truck-fast"></i> Pesanan Masuk</a></li>
            <li><a href="admin_member.php"><i class="fa-solid fa-user-group"></i> Daftar Member</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <h1 class="header-title">Pesanan Masuk</h1>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nota</th>
                        <th>Penerima</th>
                        <th>Kota</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_pesanan && $result_pesanan->num_rows > 0) {
                        while ($row = $result_pesanan->fetch_assoc()) {
                            $tagihan_rp = "Rp " . number_format($row['total_tagihan'], 0, ',', '.');
                            
                            // Pewarnaan status kaku ala brutalism
                            $status_color = $row['status'] == 'Menunggu Konfirmasi' ? 'background:#FCD34D; color:#181D31;' : 'background:#A7F3D0; color:#065F46;';

                            echo "<tr>";
                            echo "<td><strong>#SG-" . $row['id_pesanan'] . "</strong></td>";
                            echo "<td><strong>" . $row['nama_penerima'] . "</strong><br><small style='font-family:Segoe UI;'>" . $row['nomor_telepon'] . "</small></td>";
                            echo "<td>" . $row['kota_kabupaten'] . "</td>";
                            echo "<td><strong>" . $tagihan_rp . "</strong></td>";
                            echo "<td><span style='padding:4px 10px; border:2px solid #181D31; border-radius:6px; font-weight:bold; font-size:14px; " . $status_color . "'>" . $row['status'] . "</span></td>";
                            echo "<td>";
                            
                            // Tombol ACC hanya muncul jika status masih 'Menunggu Konfirmasi'
                            if ($row['status'] == 'Menunggu Konfirmasi') {
                                echo "<a href='admin_pesanan.php?aksi=konfirmasi&id=" . $row['id_pesanan'] . "' class='btn-action btn-success-brutal' onclick=\"return confirm('ACC pesanan ini? Stok produk akan otomatis terpotong.');\"><i class='fa-solid fa-check'></i> ACC Pesanan</a>";
                            } else {
                                echo "<span style='color:#666; font-family:Segoe UI; font-size:14px;'><i class='fa-solid fa-circle-check'></i> Selesai Diproses</span>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; padding: 30px; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; font-weight: bold; color: var(--color-main); text-transform: uppercase; font-size: 12px;'>Belum ada transaksi masuk dari member.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>