<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// akun yang boleh login
$valid_user = "admin";
$valid_pass = "admin123";

// cek username
if ($username !== $valid_user) {
    header("Location: login.php?error=Username tidak terdaftar");
    exit;
}

// cek password
if ($password !== $valid_pass) {
    header("Location: login.php?error=Password yang dimasukkan salah");
    exit;
}

// jika berhasil login
$_SESSION["login"] = true;
$_SESSION["username"] = $username;

header("Location: index.php");
exit;
?>
