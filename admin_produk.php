<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$pesan_error_db = "";
$query_produk = "SELECT * FROM produk ORDER BY id_produk DESC";
$result_produk = $conn->query($query_produk);

if (!$result_produk) {
    $pesan_error_db = $conn->error; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STRIKEGEAR - Kelola Produk</title>
    <link rel="stylesheet" href="style.css?v=12">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="assets/logo.png">
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
            <li><a href="admin_produk.php" class="active"><i class="fa-solid fa-box-open"></i> Kelola Produk</a></li>
            <li><a href="admin_pesanan.php"><i class="fa-solid fa-truck-fast"></i> Pesanan Masuk</a></li>
            <li><a href="admin_member.php"><i class="fa-solid fa-user-group"></i> Daftar Member</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 class="header-title" style="margin-bottom: 0;">Gudang Produk</h1>
            <a href="tambah_barang.php" class="btn-action" style="background-color: var(--color-primary); color: white; font-size: 18px; padding: 10px 20px;">
                <i class="fa-solid fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($pesan_error_db)) {
                        echo "<tr><td colspan='6' style='text-align: center; padding: 30px; background-color: #FCA5A5; color: #991B1B;'>
                                <b><i class='fa-solid fa-triangle-exclamation'></i> ERROR DATABASE:</b> <br>" . $pesan_error_db . " 
                              </td></tr>";
                    } 
                    elseif ($result_produk && $result_produk->num_rows > 0) {
                        $no = 1;
                        while ($row = $result_produk->fetch_assoc()) {
                            $harga_rp = "Rp " . number_format($row['harga'], 0, ',', '.');
                            $gambar = $row['gambar'] ? 'uploads/' . $row['gambar'] : 'https://via.placeholder.com/60?text=No+Image';
                            
                            echo "<tr>";
                            echo "<td><strong>" . $no++ . "</strong></td>";
                            echo "<td><img src='" . $gambar . "' class='img-preview' alt='Foto Produk'></td>";
                            echo "<td><strong>" . $row['nama_produk'] . "</strong></td>";
                            echo "<td>" . $harga_rp . "</td>";
                            echo "<td>" . $row['stok'] . "</td>";
                            echo "<td>
                                    <a href='edit_barang.php?id=" . $row['id_produk'] . "' class='btn-action btn-edit'><i class='fa-solid fa-pen'></i> Edit</a>
                                    <a href='hapus_barang.php?id=" . $row['id_produk'] . "' class='btn-action btn-delete' onclick=\"return confirm('Yakin ingin menghapus produk ini?');\"><i class='fa-solid fa-trash'></i> Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } 
                    else {
                        echo "<tr><td colspan='6' style='text-align: center; padding: 30px; font-family: Segoe UI, Tahoma, Geneva, Verdana, sans-serif; font-weight: bold; color: var(--color-main); text-transform: uppercase; font-size: 12px;'>Gudang masih kosong. Silakan tambah produk baru!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>