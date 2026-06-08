<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$pesan = "";

if (!isset($_GET['id'])) {
    header("Location: admin_produk.php");
    exit;
}

$id_produk = $_GET['id'];

// Ambil data produk saat ini
$ambil_data = $conn->query("SELECT * FROM produk WHERE id_produk = '$id_produk'");
if ($ambil_data->num_rows == 0) {
    header("Location: admin_produk.php");
    exit;
}
$data_saat_ini = $ambil_data->fetch_assoc();

// ===================================================
// PROSES PENGOLAHAN DATA UPDATE
// ===================================================
if (isset($_POST['edit'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $deskripsi   = $_POST['deskripsi'];

    // Menangkap data file gambar
    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    $error_file  = $_FILES['gambar']['error'];

    // Cek apakah admin mengunggah gambar baru
    if ($error_file === 4) {
        // JIKA GAMBAR TIDAK DIGANTI (Update text saja)
        $query = "UPDATE produk SET 
                  nama_produk = '$nama_produk', 
                  harga = '$harga', 
                  stok = '$stok', 
                  deskripsi = '$deskripsi' 
                  WHERE id_produk = '$id_produk'";
                  
        if ($conn->query($query)) {
            echo "<script>alert('Data produk berhasil diperbarui!'); window.location.href = 'admin_produk.php';</script>";
            exit;
        } else {
            $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Gagal update: " . $conn->error . "</div>";
        }
    } else {
        // JIKA GAMBAR DIGANTI BARU
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Format file tidak valid! Hanya menerima JPG, JPEG, dan PNG.</div>";
        } elseif ($ukuran_file > 2000000) {
            $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Ukuran gambar terlalu besar! Maksimal 2MB.</div>";
        } else {
            // Generate nama file baru
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
            $folder_tujuan = 'uploads/' . $nama_file_baru;
            
            if (move_uploaded_file($tmp_name, $folder_tujuan)) {
                // Hapus gambar lama dari folder jika ada
                if (file_exists('uploads/' . $data_saat_ini['gambar']) && !empty($data_saat_ini['gambar'])) {
                    unlink('uploads/' . $data_saat_ini['gambar']);
                }

                // Update database dengan gambar baru
                $query = "UPDATE produk SET 
                          nama_produk = '$nama_produk', 
                          harga = '$harga', 
                          stok = '$stok', 
                          deskripsi = '$deskripsi',
                          gambar = '$nama_file_baru' 
                          WHERE id_produk = '$id_produk'";
                
                if ($conn->query($query)) {
                    echo "<script>alert('Produk beserta gambar berhasil diperbarui!'); window.location.href = 'admin_produk.php';</script>";
                    exit;
                } else {
                    $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Gagal update database: " . $conn->error . "</div>";
                }
            } else {
                $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Gagal memindahkan file gambar baru!</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Strike Gear</title>
    <link rel="stylesheet" href="style.css?v=16">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&display=swap" rel="stylesheet">
    <style>
        .form-container {
            background: var(--color-surface);
            border: 3px solid var(--color-text-dark);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 8px 8px 0px var(--color-text-dark);
            max-width: 600px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-family: 'Jockey One', sans-serif;
            font-size: 20px;
            color: var(--color-text-dark);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--color-text-dark);
            border-radius: 8px;
            font-family: 'Segoe UI', sans-serif;
            font-size: 16px;
            background-color: var(--color-text-light);
            transition: all 0.2s ease;
            box-shadow: 4px 4px 0px rgba(24, 29, 49, 0.15);
        }
        .form-control:focus {
            outline: none;
            box-shadow: 4px 4px 0px var(--color-text-dark);
            transform: translate(-2px, -2px);
        }
        .btn-submit {
            background-color: #FCD34D; /* Kuning Edit */
            color: var(--color-text-dark);
            padding: 12px 20px;
            border: 2px solid var(--color-text-dark);
            border-radius: 8px;
            font-family: 'Jockey One', sans-serif;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 4px 4px 0px var(--color-text-dark);
            transition: all 0.2s ease;
            width: 100%;
            margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-text-dark);
        }
        .btn-cancel {
            display: block;
            text-align: center;
            background-color: #E7E6E1;
            color: var(--color-text-dark);
            padding: 12px 20px;
            border: 2px solid var(--color-text-dark);
            border-radius: 8px;
            font-family: 'Jockey One', sans-serif;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 4px 4px 0px var(--color-text-dark);
            transition: all 0.2s ease;
            text-decoration: none;
            width: 100%;
            margin-top: 15px;
        }
        .btn-cancel:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--color-text-dark);
        }
    </style>
</head>
<body class="admin-body">

    <nav class="sidebar">
        <div class="sidebar-header">
            <h2>STRIKE GEAR</h2>
            <p>Admin Panel</p>
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
        <h1 class="header-title">Edit Produk</h1>
        
        <div class="form-container">
            <?php echo $pesan; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" value="<?php echo htmlspecialchars($data_saat_ini['nama_produk']); ?>" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="<?php echo htmlspecialchars($data_saat_ini['harga']); ?>" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="number" name="stok" class="form-control" value="<?php echo htmlspecialchars($data_saat_ini['stok']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($data_saat_ini['deskripsi']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Produk</label>
                    
                    <div style="margin-bottom: 10px; padding: 10px; border: 2px dashed var(--color-text-dark); border-radius: 8px; text-align: center; background: white;">
                        <p style="font-family: 'Segoe UI'; font-size: 14px; margin-bottom: 10px;">Foto Saat Ini:</p>
                        <img src="uploads/<?php echo $data_saat_ini['gambar']; ?>" alt="Preview" style="max-width: 150px; border: 2px solid var(--color-text-dark); border-radius: 6px;">
                    </div>

                    <input type="file" name="gambar" class="form-control" accept=".jpg, .jpeg, .png" style="background-color: white;">
                    <small style="font-family: 'Segoe UI'; color: #666; margin-top: 5px; display: block;">*Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>

                <button type="submit" name="edit" class="btn-submit"><i class="fa-solid fa-pen-to-square"></i> Simpan Perubahan</button>
                <a href="admin_produk.php" class="btn-cancel"><i class="fa-solid fa-arrow-left"></i> Batal</a>
            </form>
        </div>
    </main>

</body>
</html>