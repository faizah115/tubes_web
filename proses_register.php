<?php
require "koneksi.php";

// Hanya boleh diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

// ambil input
$username = $_POST['username'];
$password = $_POST['password'];

// cek username sudah ada
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: register.php?error=Username sudah digunakan");
    exit;
}

// insert user (untuk sekarang masih plain text)
mysqli_query($conn, "
    INSERT INTO users (username, password, role)
    VALUES ('$username', '$password', 'user')
");

header("Location: login.php?success=Registrasi berhasil, silakan login");
exit;

