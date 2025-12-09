<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Timeout 1000 detik
$timeout = 1000;

// Jika belum ada waktu aktivitas, set sekarang
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// Jika lebih dari timeout → logout paksa
if (time() - $_SESSION['last_activity'] > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?expired=1");
    exit();
}

// Update waktu aktivitas tiap kali halaman diakses
$_SESSION['last_activity'] = time();
?>
