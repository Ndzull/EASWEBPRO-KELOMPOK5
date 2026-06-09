<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$pesan = "";

if (isset($_POST['tambah'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori    = $_POST['kategori'];
    $harga       = $_POST['harga'];
    $stok        = $_POST['stok'];
    $deskripsi   = $_POST['deskripsi'];

    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    $error_file  = $_FILES['gambar']['error'];

    if ($error_file === 4) {
        $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Wajib mengunggah foto produk!</div>";
    } else {
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Format file tidak valid! Hanya menerima JPG, JPEG, dan PNG.</div>";
        } else {
            if ($ukuran_file > 2000000) {
                $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Ukuran gambar terlalu besar! Maksimal 2MB.</div>";
            } else {
                $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
                $folder_tujuan = 'uploads/' . $nama_file_baru;
                
                if (move_uploaded_file($tmp_name, $folder_tujuan)) {
                    $query = "INSERT INTO produk (nama_produk, kategori, harga, stok, deskripsi, gambar) 
                              VALUES ('$nama_produk', '$kategori', '$harga', '$stok', '$deskripsi', '$nama_file_baru')";
                    
                    if ($conn->query($query)) {
                        echo "<script>
                                alert('Barang berhasil ditambahkan ke gudang Strike Gear!');
                                window.location.href = 'admin_produk.php';
                              </script>";
                        exit;
                    } else {
                        $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Gagal menyimpan ke database: " . $conn->error . "</div>";
                    }
                } else {
                    $pesan = "<div style='background:#FCA5A5; color:#991B1B; padding:10px; border:2px solid #181D31; border-radius:8px; margin-bottom:15px; font-weight:bold;'>Gagal mengunggah file. Pastikan folder 'uploads' sudah dibuat!</div>";
                }
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
    <title>Tambah Produk - Admin Strike Gear</title>
    <link rel="stylesheet" href="style.css?v=12">
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

        .form-group {
            margin-bottom: 20px;
        }

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
            font-family: 'Jockey One', sans-serif;
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
            background-color: var(--color-primary);
            color: white;
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
            background-color: #FCA5A5;
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
            <li><a href="#"><i class="fa-solid fa-user-group"></i> Daftar Member</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <h1 class="header-title">Tambah Produk Baru</h1>
        
        <div class="form-container">
            <?php echo $pesan; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Pancing Perkoro" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Produk</label>
                    <select name="kategori" class="form-control" required style="font-family: 'Segoe UI', sans-serif;">
                        <option value="" disabled selected hidden>-- Pilih Kategori --</option>
                        <option value="joran">Joran Pancing</option>
                        <option value="reel">Reel Pancing</option>
                        <option value="benang">Senar Pancing</option>
                        <option value="kail">Kail Pancing</option>
                        <option value="umpan">Umpan Pancing</option>
                        <option value="aksesoris">Aksesoris Pancing</option>
                        <option value="bundle">Bundle Pancing</option>

                    </select>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="Hanya angka, contoh: 450000" required>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" placeholder="Contoh: 20" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tulis spesifikasi atau deskripsi lengkap produk..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Produk</label>
                    <input type="file" name="gambar" class="form-control" accept=".jpg, .jpeg, .png" style="background-color: white;" required>
                    <small style="font-family: 'Jockey One', sans-serif; color: #666; margin-top: 5px; display: block;">Format didukung: JPG, JPEG, PNG (Maksimal 2MB).</small>
                </div>

                <button type="submit" name="tambah" class="btn-submit"><i class="fa-solid fa-save"></i> Simpan Produk</button>
                <a href="admin_produk.php" class="btn-cancel"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </form>
        </div>
    </main>

</body>
</html>