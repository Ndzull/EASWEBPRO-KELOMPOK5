<?php
// ini diganti sesuai dengan konfigurasi database kalian
$host = "localhost";
$user = "root";
$pass = "";
$db   = "easwebpro";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>