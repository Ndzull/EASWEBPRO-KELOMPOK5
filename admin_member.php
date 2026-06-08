<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    $delete = $conn->query("DELETE FROM users WHERE id = '$id_hapus'");
    
    if ($delete) {
        echo "<script>
                alert('Member berhasil dihapus dari sistem!');
                window.location.href = 'admin_member.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menghapus member: " . $conn->error . "');
              </script>";
    }
}

$query_member = "SELECT * FROM users WHERE role = 'member' ORDER BY id DESC";
$result_member = $conn->query($query_member);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeGear - Daftar Member</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=15">
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
            <li><a href="admin_pesanan.php"><i class="fa-solid fa-truck-fast"></i> Pesanan Masuk</a></li>
            <li><a href="admin_member.php" class="active"><i class="fa-solid fa-user-group"></i> Daftar Member</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <h1 class="header-title">Daftar Member Terdaftar</h1>
        
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_member && $result_member->num_rows > 0) {
                        $no = 1;
                        while ($row = $result_member->fetch_assoc()) {
                            // Menggabungkan nama depan dan belakang
                            $nama_lengkap = $row['nama_depan'] . " " . $row['nama_belakang'];
                            
                            // Mengubah format tanggal (Opsional, agar lebih enak dibaca)
                            $tgl_lahir = date("d-M-Y", strtotime($row['tanggal_lahir']));

                            echo "<tr>";
                            echo "<td><strong>" . $no++ . "</strong></td>";
                            echo "<td><strong>" . $nama_lengkap . "</strong></td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $tgl_lahir . "</td>";
                            echo "<td>
                                    <a href='admin_member.php?hapus=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Yakin ingin menghapus " . $nama_lengkap . " dari Strike Gear?');\">
                                        <i class='fa-solid fa-user-xmark'></i> Kick Member
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center; padding: 30px;'>Belum ada member yang mendaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>