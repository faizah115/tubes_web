<?php
session_start();

// Waktu timeout: 30 menit (1800 detik)
$timeout = 1800;

// Set waktu aktivitas awal
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// Auto-login jika cookie remember ada
if (
    !isset($_SESSION['username']) && 
    isset($_COOKIE['remember_username'])
) {
    $_SESSION['username'] = $_COOKIE['remember_username'];
    $_SESSION['role']     = $_COOKIE['remember_role'] ?? 'user';
    $_SESSION['login']    = true;
}

// Jika belum login → arahkan ke login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Cek timeout session
if (time() - $_SESSION['last_activity'] > $timeout) {
    session_unset();
    session_destroy();
    header("Location: login.php?expired=1");
    exit();
}

// Update aktivitas terakhir
$_SESSION['last_activity'] = time();
?>
