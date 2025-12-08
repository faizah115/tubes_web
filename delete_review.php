<?php
require "koneksi.php";
require "session_check.php";


if (!isset($_GET["id"])) {
    die("ID review tidak ditemukan!");
}

$id = intval($_GET["id"]);

$q = $conn->prepare("SELECT gambar FROM reviews WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result();
$data = $res->fetch_assoc();

if ($data) {
    if ($data["gambar"] && file_exists("uploads/" . $data["gambar"])) {
        unlink("uploads/" . $data["gambar"]);
    }
}

$del = $conn->prepare("DELETE FROM reviews WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

$buku_id = $_GET["buku_id"] ?? "";
header("Location: detail.php?id=" . $buku_id);
exit;
?>
