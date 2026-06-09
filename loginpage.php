<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status_login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$pesan = ""; 

if (isset($_POST['register'])) {
    $nama_depan = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 
    $tanggal_lahir = $_POST['tanggal_lahir'];

    $cek = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($cek->num_rows > 0) {
        $pesan = "<p style='color:red; font-size:14px; text-align:center; margin-bottom:10px;'>Email sudah terdaftar!</p>";
    } else {
        $query = "INSERT INTO users (nama_depan, nama_belakang, role, email, password, tanggal_lahir) 
                  VALUES ('$nama_depan', '$nama_belakang', '$role', '$email', '$password', '$tanggal_lahir')";
        if ($conn->query($query)) {
            $pesan = "<p style='color:green; font-size:14px; text-align:center; margin-bottom:10px;'>Registrasi berhasil! Silakan login.</p>";
        } else {
            $pesan = "<p style='color:red; font-size:14px; text-align:center; margin-bottom:10px;'>Error: " . $conn->error . "</p>";
        }
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek database
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        $_SESSION['status_login'] = true;
        $_SESSION['nama_user'] = $user_data['nama_depan'] . " " . $user_data['nama_belakang']; 
        $_SESSION['role'] = $user_data['role']; 
        $_SESSION['tanggal_lahir'] = $user_data['tanggal_lahir'];
        $_SESSION['id_user'] = $user_data['id'];

        if ($_SESSION['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    } else {
        $pesan = "<p style='color:red; font-size:14px; text-align:center; margin-bottom:10px;'>Email atau password salah!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StrikeGear - LOGIN</title>
    <link rel="icon" href="assets/logo.png">
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="login-page">
<input type="radio" name="tab" id="loginTab" checked>
<input type="radio" name="tab" id="registerTab">

<div class="container">
    <div class="card">

        <form class="form login" method="POST" action="">
            <div style="display:flex; align-items:center; justify-content:center; gap:15px; margin-bottom:15px;">
                <img src="assets/logo.png" alt="Logo Strike Gear" class="login-logo" style="width:100px; height:auto;">
                <h2 style="margin:0; spacing:0;"><span style="font-size:18px;">Welcome to</span><br><i>STRIKE GEAR</i></h2>
            </div>
            <?php echo $pesan; ?>

            <input type="email" name="email" placeholder="Email" required>
            <div style="position:relative; width:100%;">
                <input type="password" name="password" id="loginPassword" placeholder="Password" required style="padding-right:45px;">
                <button type="button" id="toggleLoginPassword" aria-label="Lihat password" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0; color:#666;">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>

            <label for="registerTab" class="btn-register">Register</label>
        </form>

        
        <form class="form register" method="POST" action="">
            <h2>REGISTER</h2>

            <input type="text" name="nama_depan" placeholder="Nama Depan" required>
            <input type="text" name="nama_belakang" placeholder="Nama Belakang" required>
            
            <p style="margin-bottom:0.5px; margin-top:8px; text-align:left; font-family: 'Jockey One', sans-serif; font-weight: 500;">Tanggal Lahir</p>
            <input type="date" name="tanggal_lahir" required>
            <!-- <input type="text" name="role" placeholder="Role" required> -->
            <select name="role" required>
                <option value="" disabled selected hidden>Role</option>
                <option value="member" style="font-family: 'Jockey One', sans-serif; font-weight: 500;">Member</option>
                <option value="admin" style="font-family: 'Jockey One', sans-serif; font-weight: 500;">Admin</option>
            </select>

            <input type="email" name="email" placeholder="Email" required>
            <div style="position:relative; width:100%;">
                <input type="password" name="password" id="registerPassword" placeholder="Password" required style="padding-right:45px;">
                <button type="button" id="toggleRegisterPassword" aria-label="Lihat password" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0; color:#666;">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <button type="submit" name="register" class="btn-login">Daftar</button>

            <label for="loginTab" class="btn-register">Kembali</label>
        </form>

    </div>
</div>
</div>

<script>
    function setupPasswordToggle(buttonId, inputId) {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);

        if (!button || !input) return;

        button.addEventListener('click', function () {
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.innerHTML = visible
                ? '<i class="fa-solid fa-eye"></i>'
                : '<i class="fa-solid fa-eye-slash"></i>';
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === '`') {
                e.preventDefault();
                button.click();
            }
        });
    }

    setupPasswordToggle('toggleLoginPassword', 'loginPassword');
    setupPasswordToggle('toggleRegisterPassword', 'registerPassword');
</script>

</body>
</html>