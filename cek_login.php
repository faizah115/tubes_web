<?php
session_start();

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($username == "admin") {

    if ($password == "admin123") {

        $_SESSION["login"] = true; 
        $_SESSION["username"] = $username;

        header("Location: index.php");
        exit;

    } else {
        // password salah
        $_SESSION["error"] = "Password yang dimasukkan salah!";
        header("Location: login.php");
        exit;
    }

} else {
    // username tidak terdaftar
    $_SESSION["error"] = "Username tidak terdaftar!";
    header("Location: login.php");
    exit;
}
