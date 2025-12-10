<?php
require "koneksi.php";
require "session_check.php";

if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]); // aman


$q = $conn->prepare("SELECT gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res  = $q->get_result();
$data = $res->fetch_assoc();

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
