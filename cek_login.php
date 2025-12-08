<?php
session_start();
require "koneksi.php";

$username = $_POST["username"];
$password = $_POST["password"];
$role     = $_POST["role"];

// LOGIN ADMIN
if ($role == "admin") {

    if ($username === "admin" && $password === "admin123") {
        $_SESSION["login"] = true;
        $_SESSION["role"] = "admin";
        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?role=admin&error=Login admin salah!");
        exit;
    }
}

// LOGIN USER BIASA
// LOGIN USER BIASA
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");

if (mysqli_num_rows($query) === 1) {
    $user = mysqli_fetch_assoc($query);

    // Karena password disimpan tanpa hash
    if ($password === $user["password"]) {
        $_SESSION["login"] = true;
        $_SESSION["role"] = "user";
        $_SESSION["username"] = $username;

        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?role=user&error=Password salah!");
        exit;
    }
} else {
    header("Location: login.php?role=user&error=Username tidak terdaftar!");
    exit;
}

