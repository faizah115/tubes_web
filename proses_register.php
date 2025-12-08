<?php
require "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

// cek jika username sudah ada
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: register.php?error=Username sudah digunakan");
    exit;
}

// simpan user baru
mysqli_query($conn, "
    INSERT INTO users (username, password, role)
    VALUES ('$username', '$password', 'user')
");

header("Location: register.php?success=Registrasi berhasil, silakan login");
exit;
?>
