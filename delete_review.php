<?php
require "koneksi.php";
require "session_check.php";

if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]); 


$q = $conn->prepare("SELECT user_id, gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res  = $q->get_result();
$data = $res->fetch_assoc();

if (!$data) {
    die("Data review tidak ditemukan!");
}


$review_user_id = $data["user_id"];

// Data user login
$current_username = $_SESSION["username"];
$current_role     = $_SESSION["role"] ?? "user";

$getUser = mysqli_query($conn, "SELECT id FROM users WHERE username='$current_username' LIMIT 1");
$u = mysqli_fetch_assoc($getUser);
$current_user_id = $u["id"];


if ($current_role !== "admin" && $current_user_id != $review_user_id) {
    die(" Anda tidak punya izin menghapus komentar ini.");
}


if ($current_role === "admin" && $current_user_id != $review_user_id) {
    $_SESSION["delete_notice"] = "Komentar Anda telah dihapus oleh admin.";
}

if ($data) {
    $gambarPath = __DIR__ . "/uploads/" . $data["gambar"];

    if ($data["gambar"] && file_exists($gambarPath)) {
        unlink($gambarPath); // Hapus file
    } else {
        error_log("Gambar tidak ditemukan atau path salah: " . $gambarPath);
    }
}

$del = $conn->prepare("DELETE FROM reviews WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

$from    = $_GET["from"] ?? "";       
$buku_id = $_GET["buku_id"] ?? "";     

if ($from === "riwayat") {
    header("Location: riwayat_halaman.php");
    exit;
}

if ($from === "detail" && $buku_id !== "") {
    header("Location: detail.php?id=" . $buku_id);
    exit;
}

header("Location: index.php");
exit;

?>
