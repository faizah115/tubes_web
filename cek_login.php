<?php
require "koneksi.php";
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

if ($username === "admin" && $password === "admin123") {
    $_SESSION["username"] = "admin";
    $_SESSION["role"] = "admin";
    $_SESSION["login"] = true;

    // Tambah user_id untuk admin (nilai 0)
    $_SESSION["user_id"] = 0;

    header("Location: index.php");
    exit;
}

$q = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
$q->bind_param("s", $username);
$q->execute();
$user = $q->get_result()->fetch_assoc();

// Username tidak ditemukan
if (!$user) {
    header("Location: login.php?error=Username tidak ditemukan");
    exit;
}

// Password cocok (plain atau hash)
if ($user["password"] === $password || password_verify($password, $user["password"])) {

    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = "user";
    $_SESSION["login"] = true;

    $_SESSION["user_id"] = $user["id"];

    // remember me
    if (isset($_POST["remember"])) {
        setcookie("remember_username", $user["username"], time() + 86400, "/");
        setcookie("remember_role", "user", time() + 86400, "/");
    }

    header("Location: index.php");
    exit;
}

// Jika password salah
header("Location: login.php?error=Password salah");
exit;
?>
